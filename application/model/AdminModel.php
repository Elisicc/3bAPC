<?php

/**
 * Handles all data manipulation of the admin part
 */
class AdminModel
{
    /**
     * Sets the deletion and suspension values
     *
     * @param $suspensionInDays
     * @param $softDelete
     * @param $userId
     */
    public static function setAccountSuspensionAndDeletionStatus($suspensionInDays, $softDelete, $userId)
    {

        // Prevent to suspend or delete own account.
        // If admin suspend or delete own account will not be able to do any action.
        if ($userId == Session::get('user_id')) {
            Session::add('feedback_negative', Text::get('FEEDBACK_ACCOUNT_CANT_DELETE_SUSPEND_OWN'));
            return false;
        }

        if ($suspensionInDays > 0) {
            $suspensionTime = time() + ($suspensionInDays * 60 * 60 * 24);
        } else {
            $suspensionTime = null;
        }

        // FYI "on" is what a checkbox delivers by default when submitted. Didn't know that for a long time :)
        if ($softDelete == "on") {
            $delete = 1;
        } else {
            $delete = 0;
        }

        // write the above info to the database
        self::writeDeleteAndSuspensionInfoToDatabase($userId, $suspensionTime, $delete);

        // if suspension or deletion should happen, then also kick user out of the application instantly by resetting
        // the user's session :)
        if ($suspensionTime != null OR $delete = 1) {
            self::resetUserSession($userId);
        }
    }

    /**
     * Simply write the deletion and suspension info for the user into the database, also puts feedback into session
     *
     * @param $userId
     * @param $suspensionTime
     * @param $delete
     * @return bool
     */
    private static function writeDeleteAndSuspensionInfoToDatabase($userId, $suspensionTime, $delete)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("UPDATE users SET user_suspension_timestamp = :user_suspension_timestamp, user_deleted = :user_deleted  WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(
                ':user_suspension_timestamp' => $suspensionTime,
                ':user_deleted' => $delete,
                ':user_id' => $userId
        ));

        if ($query->rowCount() == 1) {
            Session::add('feedback_positive', Text::get('FEEDBACK_ACCOUNT_SUSPENSION_DELETION_STATUS'));
            return true;
        }
    }

    /**
     * Kicks the selected user out of the system instantly by resetting the user's session.
     * This means, the user will be "logged out".
     *
     * @param $userId
     * @return bool
     */
    private static function resetUserSession($userId)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("UPDATE users SET session_id = :session_id  WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(
                ':session_id' => null,
                ':user_id' => $userId
        ));

        if ($query->rowCount() == 1) {
            Session::add('feedback_positive', Text::get('FEEDBACK_ACCOUNT_USER_SUCCESSFULLY_KICKED'));
            return true;
        }
    }
    /**
 * Get all user groups
 */
public static function getAllUserGroups()
{
    $database = DatabaseFactory::getFactory()->getConnection();

    $sql = "SELECT * FROM user_groups ORDER BY group_id ASC";

    $query = $database->prepare($sql);
    $query->execute();

    return $query->fetchAll();
}


public static function changeUserGroup($user_id, $group_id)
{
    $database = DatabaseFactory::getFactory()->getConnection();

    $sql = "UPDATE users
            SET user_account_type = :group_id
            WHERE user_id = :user_id
            LIMIT 1";

    $query = $database->prepare($sql);

    $query->execute(array(
        ':group_id' => $group_id,
        ':user_id' => $user_id
    ));

    if ($query->rowCount() == 1) {
        Session::add('feedback_positive', 'User group updated successfully');
        return true;
    }

    Session::add('feedback_negative', 'Failed to update user group');
    return false;
}
/**
 * Change user role/group
 *
 * @param $userId
 * @param $accountType
 * @return bool
 */
public static function changeUserRole($userId, $accountType)
{
    // eigener Account darf nicht geändert werden
    if ($userId == Session::get('user_id')) {
        Session::add('feedback_negative', 'You cannot change your own role.');
        return false;
    }

    $database = DatabaseFactory::getFactory()->getConnection();

    $query = $database->prepare("
        UPDATE users
        SET user_account_type = :user_account_type
        WHERE user_id = :user_id
        LIMIT 1
    ");

    $query->execute(array(
        ':user_account_type' => $accountType,
        ':user_id' => $userId
    ));

    if ($query->rowCount() == 1) {
        Session::add('feedback_positive', 'User role updated.');
        return true;
    }

    return false;
}
}
