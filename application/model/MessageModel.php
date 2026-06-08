<?php

class MessageModel
{
    /**
     * Get all users except current user
     */
    public static function getAllUsersExceptCurrentUser()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT
                    user_id,
                    user_name,
                    user_email,
                    user_has_avatar
                FROM users
                WHERE user_id != :current_user_id";

        $query = $database->prepare($sql);

        $query->execute(array(
            ':current_user_id' => Session::get('user_id')
        ));

        $users = $query->fetchAll();

        foreach ($users as $user) {

        if (Config::get('USE_GRAVATAR')) {

            $user->user_avatar_link =
                AvatarModel::getGravatarLinkByEmail(
                    $user->user_email
                );

        } else {

            $user->user_avatar_link =
                AvatarModel::getPublicAvatarFilePathOfUser(
                    $user->user_has_avatar,
                    $user->user_id
                );
        }
    }

    return $users;
}
    

    /**
     * Get messages between two users
     */
    public static function getMessagesBetweenUsers($other_user_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT *
                FROM messages
                WHERE
                    (sender_id = :me AND recipient_id = :other)
                OR
                    (sender_id = :other AND recipient_id = :me)
                ORDER BY created_at ASC";

        $query = $database->prepare($sql);

        $query->execute(array(
            ':me' => Session::get('user_id'),
            ':other' => $other_user_id
        ));

        return $query->fetchAll();
    }

        /**
     * Send a message
     */
    public static function sendMessage(
        $sender_id,
        $recipient_id,
        $message_content
    )
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO messages
                (
                    sender_id,
                    recipient_id,
                    message_content
                )
                VALUES
                (
                    :sender_id,
                    :recipient_id,
                    :message_content
                )";

        $query = $database->prepare($sql);

        $query->execute(array(

            ':sender_id' => $sender_id,

            ':recipient_id' => $recipient_id,

            ':message_content' => $message_content

        ));
    }
}