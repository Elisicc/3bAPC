<div class="container">
    <h1>PokedexController/index</h1>
    <div class="box">
        <h3>What happens here ?</h3>
    <ul>
        <?php foreach ($this->pokemon['results'] as $poke) : ?>

    <?php
    $pokemonId = basename(rtrim($poke['url'], '/'));
    $imageUrl = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/" . $pokemonId . ".png";
    ?>

    <div style="margin-bottom:20px;">
        <img src="<?= $imageUrl ?>" alt="<?= $poke['name'] ?>">
        <br>
        <?= ucfirst($poke['id']) ?>
        <?= ucfirst($poke['name']) ?>
        <?= ucfirst($poke['types']) ?>
    </div>

<?php endforeach; ?>
    </ul>
</div>
    </div>
</div>
