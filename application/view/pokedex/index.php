<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    $('#pokemon-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 20, 50],
        language: {
            search: "Pokémon suchen:",
            lengthMenu: "Zeige _MENU_ Pokémon",
            info: "Zeige _START_ bis _END_ von _TOTAL_ Pokémon",
            paginate: {
                previous: "Zurück",
                next: "Weiter"
            }
        }
    });
});
</script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" />
<div class="container">
    <h1>Pokédex</h1>
    <div class="box">
        <table id="pokemon-table" class="overview-table">

            <thead>
                <tr>
                    <th>Bild</th>
                    <th>#</th>
                    <th>Name</th>
                    <th>Aktion</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach ($this->pokemon['results'] as $poke) : ?>

                <?php
                $pokemonId = basename(rtrim($poke['url'], '/'));
                $imageUrl = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/" . $pokemonId . ".png";
                ?>
                <tr>
                    <td class="avatar">
                        <a href="<?= Config::get('URL') . 'pokedex/details/' . $pokemonId; ?>">
                            <img src="<?= $imageUrl ?>"
                                 alt="<?= $poke['name']; ?>"
                                 width="70">
                        </a>
                    </td>

                    <td>
                        <?= sprintf("%03d", $pokemonId); ?>
                    </td>
                    <td>
                        <a href="<?= Config::get('URL') . 'pokedex/details/' . $pokemonId; ?>">
                            <?= ucfirst($poke['name']); ?>
                        </a>
                    </td>
                    <td>
                        <a href="#">
                            Zum Team
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>