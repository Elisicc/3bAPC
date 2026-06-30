<div class="container">

    <h1>Öffentliche Pokémon-Teams</h1>

    <div class="box">

<?php

$users = [];

foreach($this->teams as $team){

    $users[$team['user_name']][] = $team;

}

?>

<?php foreach($users as $username => $pokemon): ?>

    <div style="
        border:1px solid #ddd;
        border-radius:10px;
        padding:20px;
        margin-bottom:25px;
        background:#fafafa;
    ">

        <h2>

    <?= ucfirst($username); ?>'s Team

        </h2>

        <a class="details-btn"
        href="<?= Config::get('URL'); ?>pokedex/publicTeam/<?= $pokemon[0]['user_id']; ?>">

            👁 Team ansehen

        </a>

        <br><br>

        <div style="display:flex; gap:20px; flex-wrap:wrap;">

            <?php foreach($pokemon as $member): ?>

                <div style="text-align:center;">

                    <img
                    src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?= $member['pokemon_id']; ?>.png">

                    <br>

                    <?= ucfirst($member['pokemon_name']); ?>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

<?php endforeach; ?>

    </div>

</div>