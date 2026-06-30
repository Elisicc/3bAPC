<style>

.team-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin-top:30px;
}

.team-slot{

    height:220px;

    border:2px solid #ddd;
    border-radius:10px;

    background:#f8fbfc;

    display:flex;
    justify-content:center;
    align-items:center;
    flex-direction:column;

}

.team-slot img{
    width:100px;
}

.team-slot h3{
    margin:10px 0 5px 0;
}

.team-slot p{
    color:gray;
}

.back-btn{
    display:inline-block;
    margin-top:30px;
    padding:10px 20px;
    background:#007bff;
    color:white;
    text-decoration:none;
    border-radius:5px;
}

.back-btn:hover{
    background:#0056b3;
}

</style>

<div class="container">

    <h1>Öffentliches Pokémon-Team</h1>

    <div class="box">

        <div class="team-grid">

            <?php

            $teamSlots = [];

            foreach($this->team as $member){
                $teamSlots[$member['slot']] = $member;
            }

            ?>

            <?php for($slot=1;$slot<=6;$slot++) : ?>

                <?php if(isset($teamSlots[$slot])) : ?>

                    <?php

                    $sprite = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/"
                    .$teamSlots[$slot]['pokemon_id'].".png";

                    ?>

                    <div class="team-slot">

                        <img src="<?= $sprite ?>">

                        <h3>

                            <?= ucfirst($teamSlots[$slot]['pokemon_name']); ?>

                        </h3>

                        <p>

                            #<?= sprintf("%03d",$teamSlots[$slot]['pokemon_id']); ?>

                        </p>

                        <a class="details-btn"
                           href="<?= Config::get('URL'); ?>pokedex/details/<?= $teamSlots[$slot]['pokemon_id']; ?>">

                            Details

                        </a>

                    </div>

                <?php endif; ?>

            <?php endfor; ?>

        </div>

        <a class="back-btn"
           href="<?= Config::get('URL'); ?>pokedex/publicTeams">

            ← Zurück zu allen Teams

        </a>

    </div>

</div>