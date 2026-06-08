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
    public function send()
    {
        MessageModel::sendMessage(
            Session::get('user_id'),
            Request::post('recipient_id'),
            Request::post('message_content')
    );

    Redirect::to(
        "messenger/chat/" . Request::post('recipient_id')
    );
    }


    
}