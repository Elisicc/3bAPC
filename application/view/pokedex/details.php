<style>

.pokemon-card{
    max-width:900px;
    margin:30px auto;
    border:1px solid #ddd;
    border-radius:12px;
    background:white;
    padding:30px;
}

.pokemon-header{
    text-align:center;
    margin-bottom:30px;
}

.pokemon-header h1{
    margin:0;
    font-size:42px;
}

.pokemon-number{
    color:#777;
    font-size:20px;
}

.pokemon-info{
    display:flex;
    gap:40px;
    align-items:center;
    justify-content:center;
    margin-bottom:40px;
}

.pokemon-image img{
    width:250px;
}

.info-table{
    font-size:18px;
}

.info-table p{
    margin:12px 0;
}

.stats{
    margin-top:30px;
}

.stat{
    margin-bottom:15px;
}

.stat-name{
    font-weight:bold;
    margin-bottom:5px;
}

.bar{
    width:100%;
    height:20px;
    background:#ddd;
    border-radius:10px;
    overflow:hidden;
}

.fill{
    height:20px;
    background:#4CAF50;
}

.back-btn{
    display:inline-block;
    margin-top:30px;
    padding:10px 20px;
    background:#007bff;
    color:white;
    text-decoration:none;
    border-radius:6px;
}

.back-btn:hover{
    background:#0056b3;
}

</style>

<div class="container">

<div class="pokemon-card">

    <div class="pokemon-header">

        <h1><?= ucfirst($this->pokemon['name']); ?></h1>

        <div class="pokemon-number">
            #<?= sprintf("%03d",$this->pokemon['id']); ?>
        </div>

    </div>

    <div class="pokemon-info">

        <div class="pokemon-image">

            <img src="<?= $this->pokemon['sprites']['other']['official-artwork']['front_default']; ?>">

        </div>

        <div class="info-table">

            <p>
                <strong>Typ:</strong>

                <?php
                $types = [];

                foreach($this->pokemon['types'] as $type){
                    $types[] = ucfirst($type['type']['name']);
                }

                echo implode(" / ", $types);
                ?>
            </p>

            <p>
                <strong>Größe:</strong>
                <?= $this->pokemon['height']/10; ?> m
            </p>

            <p>
                <strong>Gewicht:</strong>
                <?= $this->pokemon['weight']/10; ?> kg
            </p>

            <p>
                <strong>Fähigkeiten:</strong><br>

                <?php foreach($this->pokemon['abilities'] as $ability): ?>

                    • <?= ucfirst(str_replace('-', ' ', $ability['ability']['name'])); ?><br>

                <?php endforeach; ?>

            </p>

        </div>

    </div>

    <h2>Basiswerte</h2>

    <div class="stats">

        <?php foreach($this->pokemon['stats'] as $stat): ?>

            <div class="stat">

                <div class="stat-name">

                    <?= ucfirst(str_replace('-', ' ', $stat['stat']['name'])); ?>

                    (<?= $stat['base_stat']; ?>)

                </div>

                <div class="bar">

                    <div class="fill"
                        style="width:<?= min($stat['base_stat'],150)/150*100 ?>%;">
                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

    <a class="back-btn" href="<?= Config::get('URL'); ?>pokedex">
        ← Zurück zum Pokédex
    </a>

</div>

</div>