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
            'pictures' => PictureModel::getPictureOfCurrentUser()
        ));
    }

    public function upload(){

        PictureModel::uploadPicture();
        Redirect::to('picture/index');

    }











}
