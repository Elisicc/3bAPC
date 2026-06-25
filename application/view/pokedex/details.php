<div class="container">

    <h1>
        #<?= $this->pokemon['id']; ?>
        <?= ucfirst($this->pokemon['name']); ?>
    </h1>

    <img src="<?= $this->pokemon['sprites']['front_default']; ?>">

    <h3>Typen</h3>

    <ul>
        <?php foreach ($this->pokemon['types'] as $type) : ?>
            <li>
                <?= ucfirst($type['type']['name']); ?>
            </li>
        <?php endforeach; ?>

        <?= ucfirst($this->pokemon['weight']) ?>
        <?= ucfirst($this->pokemon['height']) ?>


    </ul>

</div>