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
            'users' => MessageModel::getAllUsersExceptCurrentUser()
        ));
    }

    /**
     * Open chat with specific user
     */
    public function chat($user_id)
    {
        $this->View->render('messenger/chat', array(
            'users' => MessageModel::getAllUsersExceptCurrentUser(),
            'messages' => MessageModel::getMessagesBetweenUsers($user_id),
            'chat_partner_id' => $user_id
        ));
    }
}