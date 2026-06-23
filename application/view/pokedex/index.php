<div class="container">
    <h1>PokedexController/index</h1>
    <div class="box">
        <h3>What happens here ?</h3>
        <p>Test</p>
    <ul>
        <?php foreach ($this->pokemon['results'] as $poke) : ?>
            <li>
                <?php echo ucfirst($poke['name']); ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
    </div>
</div>
