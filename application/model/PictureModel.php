<?php

class PictureModel
{
    private static function getPictureBasePath()
    {
        return realpath(dirname(__FILE__) . '/../../') . '/_pictures/user_uploads/';
    }

    public static function getPicturesOfCurrentUser()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT picture_id, user_id, filename, original_filename, mime_type, is_public, created_at
                FROM pictures
                WHERE user_id = :user_id
                ORDER BY created_at DESC";

        $query = $database->prepare($sql);
        $query->execute(array(
            ':user_id' => Session::get('user_id')
        ));

        return $query->fetchAll();
    }
 
    public static function uploadPicture()
    {
        if (!isset($_FILES['picture_file'])) {
            Session::add('feedback_negative', 'No picture uploaded.');
            return false;
        }

        if ($_FILES['picture_file']['error'] !== UPLOAD_ERR_OK) {
            Session::add('feedback_negative', 'Picture upload failed.');
            return false;
        }

        if ($_FILES['picture_file']['size'] > 5000000) {
            Session::add('feedback_negative', 'Picture is too big. Maximum size is 5 MB.');
            return false;
        }

        $image_info = getimagesize($_FILES['picture_file']['tmp_name']);

        if (!$image_info) {
            Session::add('feedback_negative', 'Uploaded file is not a valid image.');
            return false;
        }

        $mime_type = $image_info['mime'];

        $allowed_types = array(
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp'
        );

        if (!array_key_exists($mime_type, $allowed_types)) {
            Session::add('feedback_negative', 'Only JPG, PNG, GIF and WEBP images are allowed.');
            return false;
        }

        $user_id = Session::get('user_id');
        $user_folder = self::getPictureBasePath() . $user_id . '/';

        if (!is_dir($user_folder)) {
            mkdir($user_folder, 0755, true);
        }

        if (!is_writable($user_folder)) {
            Session::add('feedback_negative', 'Picture folder is not writable.');
            return false;
        }

        $extension = $allowed_types[$mime_type];
        $filename = sha1(uniqid('', true)) . '.' . $extension;
        $target_path = $user_folder . $filename;

        if (!move_uploaded_file($_FILES['picture_file']['tmp_name'], $target_path)) {
            Session::add('feedback_negative', 'Could not save uploaded picture.');
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO pictures
                    (user_id, filename, original_filename, mime_type, is_public)
                VALUES
                    (:user_id, :filename, :original_filename, :mime_type, 0)";

        $query = $database->prepare($sql);
        $query->execute(array(
            ':user_id' => $user_id,
            ':filename' => $filename,
            ':original_filename' => $_FILES['picture_file']['name'],
            ':mime_type' => $mime_type
        ));

        if ($query->rowCount() == 1) {
            Session::add('feedback_positive', 'Picture uploaded successfully.');
            return true;
        }

        Session::add('feedback_negative', 'Could not save picture to database.');
        return false;
    }
    public static function showPicture($picture_id)
    {
        if (!$picture_id) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT picture_id, user_id, filename, mime_type, is_public
                FROM pictures
                WHERE picture_id = :picture_id
                AND (user_id = :user_id OR is_public = 1)
                LIMIT 1";

        $query = $database->prepare($sql);
        $query->execute(array(
            ':picture_id' => $picture_id,
            ':user_id' => Session::get('user_id')
        ));

        $picture = $query->fetch();

        if (!$picture) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $file_path = self::getPictureBasePath() . $picture->user_id . '/' . $picture->filename;

        if (!file_exists($file_path)) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        header('Content-Type: ' . $picture->mime_type);
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    }
}