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

        $this->View->render('pokedex/index',[
            'pokemon' => $pokemon
        ]);
    }


    public function Details($id){

    $model = new PokedexModel();

    $pokemon = $model->getDetails($id);

    $this->View->render('pokedex/details', [
        'pokemon' => $pokemon   
    ]);

    }
}