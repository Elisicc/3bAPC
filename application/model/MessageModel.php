<?php

class MessageModel
{
    /**
     * Get all users except current user
     */
    public static function getAllUsersExceptCurrentUser()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user_id, user_name
                FROM users
                WHERE user_id != :current_user_id";

        $query = $database->prepare($sql);

        $query->execute(array(
            ':current_user_id' => Session::get('user_id')
        ));

        return $query->fetchAll();
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
}