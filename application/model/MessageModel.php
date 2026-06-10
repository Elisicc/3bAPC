<?php

class MessageModel
{
    /**
     * Get all users except current user
     */
    public static function getAllUsersExceptCurrentUser()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_get_all_users_except(:current_user_id)");
        $query->execute(array(':current_user_id' => Session::get('user_id')));
        $users = $query->fetchAll();
        $query->closeCursor();

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
        $query = $database->prepare("CALL sp_get_messages_between(:me, :other)");
        $query->execute(array(
            ':me' => Session::get('user_id'),
            ':other' => $other_user_id
        ));

        $results = $query->fetchAll();
        $query->closeCursor();
        return $results;
    }

    public static function getGroupsForCurrentUser()
    {
        self::ensureGroupTablesExist();

        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("CALL sp_get_groups_for_user(:user_id)");
        $query->execute(array(':user_id' => Session::get('user_id')));

        $results = $query->fetchAll();
        $query->closeCursor();
        return $results;
    }

    public static function ensureGroupTablesExist()
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        // Call stored procedure that ensures group tables exist (create if not exists)
        // The procedure `sp_ensure_group_tables` should be created in the database beforehand.
        try {
            $database->exec("CALL sp_ensure_group_tables()");
        } catch (Exception $e) {
            // fallback: create tables directly if stored procedure not present
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
    }

    public static function createGroup($group_name, $created_by, array $member_ids)
    {
        if (empty($group_name) || empty($member_ids)) {
            return false;
        }

        self::ensureGroupTablesExist();

        $database = DatabaseFactory::getFactory()->getConnection();

        // create group via stored procedure which returns the new group_id
        $stmt = $database->prepare("CALL sp_create_group(:group_name, :created_by)");
        $stmt->execute(array(':group_name' => $group_name, ':created_by' => $created_by));
        $row = $stmt->fetch();
        $stmt->closeCursor();
        $group_id = $row ? $row->group_id : null;
        $member_ids[] = $created_by;
        $member_ids = array_unique($member_ids);

        $stmtMember = $database->prepare("CALL sp_add_group_member(:group_id, :user_id)");
        foreach ($member_ids as $member_id) {
            $stmtMember->execute(array(':group_id' => $group_id, ':user_id' => $member_id));
            $stmtMember->closeCursor();
        }

        return $group_id;
    }

    public static function getGroupById($group_id)
    {
        self::ensureGroupTablesExist();

        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("CALL sp_get_group_detail(:group_id, :detail_type)");
        $query->execute(array(':group_id' => $group_id, ':detail_type' => 'group'));
        $result = $query->fetch();
        $query->closeCursor();
        return $result;
    }

    public static function getGroupMembers($group_id)
    {
        self::ensureGroupTablesExist();

        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("CALL sp_get_group_detail(:group_id, :detail_type)");
        $query->execute(array(':group_id' => $group_id, ':detail_type' => 'members'));
        $results = $query->fetchAll();
        $query->closeCursor();
        return $results;
    }

    public static function getGroupMessages($group_id)
    {
        self::ensureGroupTablesExist();

        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("CALL sp_get_group_detail(:group_id, :detail_type)");
        $query->execute(array(':group_id' => $group_id, ':detail_type' => 'messages'));
        $results = $query->fetchAll();
        $query->closeCursor();
        return $results;
    }

    public static function isUserInGroup($group_id, $user_id)
    {
        self::ensureGroupTablesExist();

        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("CALL sp_is_user_in_group(:group_id, :user_id)");
        $query->execute(array(':group_id' => $group_id, ':user_id' => $user_id));
        $result = $query->fetch();
        $query->closeCursor();
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

            $query = $database->prepare("CALL sp_insert_message(:sender_id, :recipient_id, :group_id, :message_content)");
            $query->execute(array(
                ':sender_id' => $sender_id,
                ':recipient_id' => null,
                ':group_id' => $group_id,
                ':message_content' => $message_content
            ));
            $query->closeCursor();

            return;
        }

        $query = $database->prepare("CALL sp_insert_message(:sender_id, :recipient_id, :group_id, :message_content)");
        $query->execute(array(
            ':sender_id' => $sender_id,
            ':recipient_id' => $recipient_id,
            ':group_id' => null,
            ':message_content' => $message_content
        ));
        $query->closeCursor();
    }

    public static function getUnreadMessagesCount()
{
    $database = DatabaseFactory::getFactory()->getConnection();
    $query = $database->prepare("CALL sp_count_unread(:user_id, :other_user_id)");
    $query->execute(array(':user_id' => Session::get('user_id'), ':other_user_id' => null));
    $result = $query->fetch();
    $query->closeCursor();
    return $result ? $result->unread : 0;
}
public static function getUnreadMessagesFromUser($other_user_id)
{
    $database = DatabaseFactory::getFactory()->getConnection();
    $query = $database->prepare("CALL sp_count_unread(:user_id, :other_user_id)");
    $query->execute(array(':user_id' => Session::get('user_id'), ':other_user_id' => $other_user_id));
    $result = $query->fetch();
    $query->closeCursor();
    return $result ? $result->unread : 0;
}
public static function markMessagesAsRead($other_user_id)
{
    $database = DatabaseFactory::getFactory()->getConnection();
    $query = $database->prepare("CALL sp_mark_messages_read(:other, :me)");
    $query->execute(array(':other' => $other_user_id, ':me' => Session::get('user_id')));
    $query->closeCursor();
}
}
