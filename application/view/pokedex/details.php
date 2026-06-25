<div class="container">

    <div class="box">

        <h1>
            #<?= sprintf("%03d", $this->pokemon['id']); ?>
            <?= ucfirst($this->pokemon['name']); ?>
        </h1>

        <img src="<?= $this->pokemon['sprites']['front_default']; ?>" alt="<?= $this->pokemon['name']; ?>">

        <hr>

        <h3>Informationen</h3>

        <p>
            <strong>Größe:</strong>
            <?= $this->pokemon['height'] / 10; ?> m
        </p>

        <p>
            <strong>Gewicht:</strong>
            <?= $this->pokemon['weight'] / 10; ?> kg
        </p>

        <h3>Typen</h3>

        <ul>
            <?php foreach ($this->pokemon['types'] as $type) : ?>
                <li><?= ucfirst($type['type']['name']); ?></li>
            <?php endforeach; ?>
        </ul>

    </div>

</div>