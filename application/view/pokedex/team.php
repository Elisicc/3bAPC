<style>
.team-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
    margin-top: 30px;
}
.team-slot {
    height: 180px;
    border: 2px solid #dcdcdc;
    border-radius: 10px;
    background-color: #f8fbfc;

    display: flex;
    justify-content: center;
    align-items: center;

    text-decoration: none;

    transition: all 0.2s ease;
}
.team-slot:hover {
    background-color: #eef7fa;
    border-color: #6aa9ff;
    transform: scale(1.03);
}

.plus {
    font-size: 70px;
    color: #999;
    font-weight: bold;
}

.team-title {
    margin-bottom: 10px;
}
</style>

<div class="container team-page">

    <h1 class="team-title">Mein Pokémon-Team</h1>

    <div class="box">

        <h3>Wähle einen freien Platz für dein Pokémon</h3>

        <div class="team-grid">

            <?php for ($slot = 1; $slot <= 6; $slot++) : ?>

                <a class="team-slot"
                   href="<?= Config::get('URL') . 'pokedex/saveTeam/' . $this->pokemonId . '/' . $slot; ?>">

                    <div class="plus">+</div>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</div>