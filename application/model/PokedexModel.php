<?php

class PokedexModel{

public function getPokemon()
{
    $url = "https://pokeapi.co/api/v2/pokemon?limit=10";

    $response = file_get_contents($url);

    return json_decode($response, true);
}



}

