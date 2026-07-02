<style>
.team-grid {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin-top:30px;
}

.team-slot{
    height:180px;

    border:2px solid #ddd;
    border-radius:10px;

    background:#f8fbfc;

    display:flex;
    justify-content:center;
    align-items:center;
    flex-direction:column;

    text-decoration:none;
}

.team-slot:hover{
    background:#eef7fa;
}

.plus{
    font-size:70px;
    color:gray;
    font-weight:bold;
}

.team-slot img{
    width:90px;
}

.team-slot p{
    margin-top:10px;
    font-weight:bold;
    color:black;
}

.remove-btn{
    margin-top:10px;
    padding:6px 12px;
    background:#dc3545;
    color:white;
    text-decoration:none;
    border-radius:5px;
    font-size:13px;
}

.remove-btn:hover{
    background:#b02a37;
}
.details-btn{
    margin-top:8px;
    padding:6px 12px;
    background:#007bff;
    color:white;
    text-decoration:none;
    border-radius:5px;
    font-size:13px;
}

.details-btn:hover{
    background:#0056b3;
}

.button-group{
    display:flex;
    gap:10px;
    margin-top:10px;
}
</style>

<div class="container">

    <h1>Mein Pokémon-Team</h1>

    <div class="box">

        <h3>Wähle einen freien Platz</h3>

        <div style="margin-bottom:25px;">

        <?php if($this->isPublic): ?>

            <a class="details-btn"
            href="<?= Config::get('URL'); ?>pokedex/setTeamPrivate">

                Team privat machen

            </a>

        <?php else: ?>

            <a class="details-btn"
            href="<?= Config::get('URL'); ?>pokedex/setTeamPublic">

                Team veröffentlichen

            </a>

        <?php endif; ?>

        </div>

        <div class="team-grid">

        <?php

        $teamSlots = [];

        foreach($this->team as $member){
            $teamSlots[$member['slot']] = $member;
        }

        ?>

        <?php for($slot = 1; $slot <= 6; $slot++) : ?>

            <?php if(isset($teamSlots[$slot])) : ?>

                <div class="team-slot">

                    <?php
                    $sprite = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/" . $teamSlots[$slot]['pokemon_id'] . ".png";                    ?>

                    <img src="<?= $sprite ?>">

                    <p style="font-size:18px; margin-bottom:5px;">
                        <?= ucfirst($teamSlots[$slot]['pokemon_name']); ?>
                    </p>

                    <p style="font-size:14px; color:gray;">
                        #<?= sprintf("%03d", $teamSlots[$slot]['pokemon_id']); ?>
                    </p>

                    <div class="button-group">

                    <a class="details-btn"
                    href="<?= Config::get('URL'); ?>pokedex/details/<?= $teamSlots[$slot]['pokemon_id']; ?>">

                        Details

                    </a>

                    <a class="remove-btn"
                    href="<?= Config::get('URL'); ?>pokedex/removePokemon/<?= $this->pokemonId; ?>/<?= $slot; ?>"
                    onclick="return confirm('Möchtest du dieses Pokémon wirklich aus deinem Team entfernen?');">

                        Entfernen

                    </a>

                </div>

                </div>

            <?php else : ?>

                <a class="team-slot"
                    href="<?= Config::get('URL').'pokedex/index/'.$slot; ?>">

                    <div class="plus">+</div>

                </a>

            <?php endif; ?>

        <?php endfor; ?>

        </div>

    </div>

</div>