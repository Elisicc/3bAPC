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
    public function team($id)
    {
        $model = new PokedexModel();

        $pokemon = $model->getDetails($id);

        $this->View->render('pokedex/team', [
            'pokemon' => $pokemon,
            'pokemonId' => $id
        ]);
    }

    public function saveTeam($pokemonId, $slot)
{
    $model = new PokedexModel();

    $model->savePokemonToTeam(
        Session::get('user_id'),
        $pokemonId,
        $slot
    );

    Redirect::to('pokedex');
}
}