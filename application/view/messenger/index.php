<div class="container">

    <h1>Messenger</h1>

    <div class="box">

        <h3>Users</h3>

        <?php foreach ($this->users as $user) { ?>

            <div style="margin-bottom: 10px;">

                <a href="<?= Config::get('URL'); ?>messenger/chat/<?= $user->user_id; ?>">

                    <?= $user->user_name; ?>

                </a>

            </div>

        <?php } ?>

    </div>

</div>