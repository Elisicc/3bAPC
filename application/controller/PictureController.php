<?php

class PictureController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        Auth::checkAuthentication();
    }

    public function index()
    {
        $this->View->render('picture/index', array(
            'pictures' => PictureModel::getPicturesOfCurrentUser()
        ));
    }

    public function upload_action()
    {
        PictureModel::uploadPicture();
        Redirect::to('picture/index');
    }

    public function show($picture_id)
    {
        PictureModel::showPicture($picture_id);
    }
}