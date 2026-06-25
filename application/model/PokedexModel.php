<?php

class PokedexModel{

public function getPokemon()
{
    $url = "https://pokeapi.co/api/v2/pokemon?limit=1025";

    $response = file_get_contents($url);

    return json_decode($response, true);
}

public function getDetails($id){

    $url = "https://pokeapi.co/api/v2/pokemon/" . $id;

    $response = file_get_contents($url);

    return json_decode($response, true);

}


}

