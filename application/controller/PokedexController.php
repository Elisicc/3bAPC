<?php

class PokedexController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->View->render('pokedex/index');
    }
}
