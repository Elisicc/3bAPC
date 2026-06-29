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
    public function team($pokemonId)
{
    $model = new PokedexModel();

    $pokemon = $model->getDetails($pokemonId);

    $team = $model->getUserTeam(Session::get('user_id'));

    $this->View->render('pokedex/team', [
        'pokemon' => $pokemon,
        'pokemonId' => $pokemonId,
        'team' => $team
    ]);
}

public function saveTeam($pokemonId, $slot)
{
    $model = new PokedexModel();

    $pokemon = $model->getDetails($pokemonId);

    $model->savePokemonToTeam(
        Session::get('user_id'),
        $pokemonId,
        $pokemon['name'],
        $slot
    );

    Redirect::to('pokedex');
}
    public function removePokemon($pokemonId, $slot)
{
    $model = new PokedexModel();

    $model->removePokemonFromTeam(
        Session::get('user_id'),
        $slot
    );

    Redirect::to('pokedex/team/' . $pokemonId);
}





}