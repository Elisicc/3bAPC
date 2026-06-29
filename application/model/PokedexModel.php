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
public function savePokemonToTeam($userId, $pokemonId, $pokemonName, $slot)
{
    $database = DatabaseFactory::getFactory()->getConnection();

    $delete = $database->prepare("
        DELETE FROM pokemon_team
        WHERE user_id = :user_id
        AND slot = :slot
    ");

    $delete->execute([
        ':user_id' => $userId,
        ':slot' => $slot
    ]);

    $insert = $database->prepare("
    INSERT INTO pokemon_team
    (user_id, pokemon_id, pokemon_name, slot)
    VALUES (:user_id, :pokemon_id, :pokemon_name, :slot)
");

    $insert->execute([
    ':user_id' => $userId,
    ':pokemon_id' => $pokemonId,
    ':pokemon_name' => ucfirst($pokemonName),
    ':slot' => $slot
]);
}

public function getUserTeam($userId)
{
    $database = DatabaseFactory::getFactory()->getConnection();

    $sql = "SELECT * FROM pokemon_team
            WHERE user_id = :user_id
            ORDER BY slot ASC";

    $query = $database->prepare($sql);

    $query->execute(array(
        ':user_id' => $userId
    ));

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

public function removePokemonFromTeam($userId, $slot)
{
    $database = DatabaseFactory::getFactory()->getConnection();

    $sql = "DELETE FROM pokemon_team
            WHERE user_id = :user_id
            AND slot = :slot";

    $query = $database->prepare($sql);

    $query->execute(array(
        ':user_id' => $userId,
        ':slot' => $slot
    ));
}


}

