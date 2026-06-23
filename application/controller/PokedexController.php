<?php

class PokedexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $model = new PokedexModel();

        $pokemon = $model->getPokemon();

        var_dump($pokemon);

        $this->View->render('pokedex/index');
    }
}