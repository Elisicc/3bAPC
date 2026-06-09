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
        
        $user->unread_messages =
            self::getUnreadMessagesFromUser(
                $user->user_id
        );
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

    public static function getGroupsForCurrentUser()
    {
        self::ensureGroupTablesExist();

        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT g.*
                FROM chat_groups g
                INNER JOIN chat_group_members gm ON g.group_id = gm.group_id
                WHERE gm.user_id = :user_id
                ORDER BY g.created_at DESC";

        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => Session::get('user_id')));

        return $query->fetchAll();
    }

    public static function ensureGroupTablesExist()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $database->exec("CREATE TABLE IF NOT EXISTS chat_groups (
            group_id INT AUTO_INCREMENT PRIMARY KEY,
            group_name VARCHAR(255) NOT NULL,
            created_by INT NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $database->exec("CREATE TABLE IF NOT EXISTS chat_group_members (
            group_id INT NOT NULL,
            user_id INT NOT NULL,
            PRIMARY KEY (group_id, user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $database->exec("CREATE TABLE IF NOT EXISTS group_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            group_id INT NOT NULL,
            sender_id INT NOT NULL,
            message_content TEXT NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    public static function createGroup($group_name, $created_by, array $member_ids)
    {
        if (empty($group_name) || empty($member_ids)) {
            return false;
        }

        self::ensureGroupTablesExist();

        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "INSERT INTO chat_groups (group_name, created_by) VALUES (:group_name, :created_by)";
        $query = $database->prepare($sql);
        $query->execute(array(
            ':group_name' => $group_name,
            ':created_by' => $created_by
        ));

        $group_id = $database->lastInsertId();
        $member_ids[] = $created_by;
        $member_ids = array_unique($member_ids);

        $sql = "INSERT INTO chat_group_members (group_id, user_id) VALUES (:group_id, :user_id)";
        $query = $database->prepare($sql);

        foreach ($member_ids as $member_id) {
            $query->execute(array(
                ':group_id' => $group_id,
                ':user_id' => $member_id
            ));
        }

        return $group_id;
    }

    public static function getGroupById($group_id)
    {
        self::ensureGroupTablesExist();

        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT * FROM chat_groups WHERE group_id = :group_id";
        $query = $database->prepare($sql);
        $query->execute(array(':group_id' => $group_id));

        return $query->fetch();
    }

    public static function getGroupMembers($group_id)
    {
        self::ensureGroupTablesExist();

        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT u.user_id, u.user_name, u.user_email, u.user_has_avatar
                FROM users u
                INNER JOIN chat_group_members gm ON u.user_id = gm.user_id
                WHERE gm.group_id = :group_id";

        $query = $database->prepare($sql);
        $query->execute(array(':group_id' => $group_id));

        return $query->fetchAll();
    }

    public static function getGroupMessages($group_id)
    {
        self::ensureGroupTablesExist();

        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT *
                FROM group_messages
                WHERE group_id = :group_id
                ORDER BY created_at ASC";

        $query = $database->prepare($sql);
        $query->execute(array(':group_id' => $group_id));

        return $query->fetchAll();
    }

    public static function isUserInGroup($group_id, $user_id)
    {
        self::ensureGroupTablesExist();

        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT COUNT(*) AS count
                FROM chat_group_members
                WHERE group_id = :group_id
                AND user_id = :user_id";

        $query = $database->prepare($sql);
        $query->execute(array(
            ':group_id' => $group_id,
            ':user_id' => $user_id
        ));

        $result = $query->fetch();
        return !empty($result) && $result->count > 0;
    }

    /**
     * Send a message
     */
    public static function sendMessage(
        $sender_id,
        $recipient_id,
        $message_content,
        $group_id = null
    )
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        if ($group_id) {
            self::ensureGroupTablesExist();

            $sql = "INSERT INTO group_messages
                    (
                        group_id,
                        sender_id,
                        message_content
                    )
                    VALUES
                    (
                        :group_id,
                        :sender_id,
                        :message_content
                    )";

            $query = $database->prepare($sql);
            $query->execute(array(
                ':group_id' => $group_id,
                ':sender_id' => $sender_id,
                ':message_content' => $message_content
            ));

            return;
        }

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

    public static function getUnreadMessagesCount()
{
    $database = DatabaseFactory::getFactory()->getConnection();

    $sql = "SELECT COUNT(*) as unread
            FROM messages
            WHERE recipient_id = :user_id
            AND is_read = 0";

    $query = $database->prepare($sql);

    $query->execute(array(
        ':user_id' => Session::get('user_id')
    ));

    return $query->fetch()->unread;
}
public static function getUnreadMessagesFromUser($other_user_id)
{
    $database = DatabaseFactory::getFactory()->getConnection();

    $sql = "SELECT COUNT(*) AS unread
            FROM messages
            WHERE sender_id = :other
            AND recipient_id = :me
            AND is_read = 0";

    $query = $database->prepare($sql);

    $query->execute(array(
        ':other' => $other_user_id,
        ':me' => Session::get('user_id')
    ));

    return $query->fetch()->unread;
}
public static function markMessagesAsRead($other_user_id)
{
    $database = DatabaseFactory::getFactory()->getConnection();

    $sql = "UPDATE messages
            SET is_read = 1
            WHERE sender_id = :other
            AND recipient_id = :me
            AND is_read = 0";

    $query = $database->prepare($sql);

    $query->execute(array(
        ':other' => $other_user_id,
        ':me' => Session::get('user_id')
    ));
}
}
