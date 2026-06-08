<?php

class MessengerController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        Auth::checkAuthentication();
    }

    /**
     * Messenger overview
     */
    public function index()
    {
        $this->View->render('messenger/index', array(
            'users' => MessageModel::getAllUsersExceptCurrentUser(),
            'groups' => MessageModel::getGroupsForCurrentUser()
        ));
    }
    

    /**
     * Open chat with specific user
     */
    public function chat($user_id = null)
    {
        if (!$user_id) {
            Redirect::to('messenger/index');
        }

        $this->View->render('messenger/chat', array(
            'users' => MessageModel::getAllUsersExceptCurrentUser(),
            'groups' => MessageModel::getGroupsForCurrentUser(),
            'messages' => MessageModel::getMessagesBetweenUsers($user_id),
            'chat_partner_id' => $user_id
        ));
    }

    public function chatGroup($group_id = null)
    {
        if (!$group_id) {
            Redirect::to('messenger/index');
        }

        $group = MessageModel::getGroupById($group_id);

        if (!$group || !MessageModel::isUserInGroup($group_id, Session::get('user_id'))) {
            Redirect::to('messenger/index');
        }

        $this->View->render('messenger/chat', array(
            'users' => MessageModel::getAllUsersExceptCurrentUser(),
            'groups' => MessageModel::getGroupsForCurrentUser(),
            'messages' => MessageModel::getGroupMessages($group_id),
            'chat_group_id' => $group_id,
            'group' => $group,
            'group_members' => MessageModel::getGroupMembers($group_id)
        ));
    }

    public function createGroup()
    {
        $group_name = trim(Request::post('group_name'));
        $members = Request::post('group_members');

        if (empty($group_name) || !is_array($members) || count($members) === 0) {
            Session::add('feedback_negative', 'Bitte gib einen Gruppennamen an und wähle mindestens einen Benutzer aus.');
            Redirect::to('messenger/index');
        }

        $group_id = MessageModel::createGroup($group_name, Session::get('user_id'), $members);

        if ($group_id) {
            Session::add('feedback_positive', 'Gruppenchat erfolgreich erstellt.');
            Redirect::to('messenger/chatgroup/' . $group_id);
        }

        Session::add('feedback_negative', 'Gruppenchat konnte nicht erstellt werden.');
        Redirect::to('messenger/index');
    }

    public function send()
    {
        $group_id = Request::post('group_id');
        $recipient_id = Request::post('recipient_id');
        $message_content = trim(Request::post('message_content'));

        if (empty($message_content)) {
            Session::add('feedback_negative', 'Bitte gib eine Nachricht ein.');
            Redirect::to('messenger/index');
        }

        if (!empty($group_id)) {
            MessageModel::sendMessage(
                Session::get('user_id'),
                null,
                $message_content,
                $group_id
            );

            Redirect::to('messenger/chatgroup/' . $group_id);
        }

        if (!empty($recipient_id)) {
            MessageModel::sendMessage(
                Session::get('user_id'),
                $recipient_id,
                $message_content
            );

            Redirect::to('messenger/chat/' . $recipient_id);
        }

        Session::add('feedback_negative', 'Ungültiger Empfänger. Bitte wähle einen Benutzer oder eine Gruppe aus.');
        Redirect::to('messenger/index');
    }
}
