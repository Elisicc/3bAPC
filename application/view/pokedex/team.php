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
}
</style>

<div class="container">

    <h1>Mein Pokémon-Team</h1>

    <div class="box">

        <h3>Wähle einen freien Platz</h3>

        <div class="team-grid">

        <?php

        $teamSlots = [];

        foreach($this->team as $member){
            $teamSlots[$member['slot']] = $member['pokemon_id'];
        }

        ?>

        <?php for($slot = 1; $slot <= 6; $slot++) : ?>

            <?php if(isset($teamSlots[$slot])) : ?>

                <div class="team-slot">

                    <?php
                    $sprite = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/".$teamSlots[$slot].".png";
                    ?>

                    <img src="<?= $sprite ?>">

                    <p>#<?= sprintf("%03d",$teamSlots[$slot]); ?></p>

                </div>

            <?php else : ?>

                <a class="team-slot"
                   href="<?= Config::get('URL').'pokedex/saveTeam/'.$this->pokemonId.'/'.$slot; ?>">

                    <div class="plus">+</div>

                </a>

            <?php endif; ?>

        <?php endfor; ?>

        </div>

    </div>

</div>