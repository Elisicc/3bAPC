<div class="container">

    <h1>Messenger Chat</h1>

    <div class="box">

        <div style="display:flex; gap:40px;">

            <!-- USER LIST -->
            <div style="width:200px;">

                <h3>Users</h3>

                <?php foreach ($this->users as $user) { ?>

                    <div style="margin-bottom:10px;">

                        <a href="<?= Config::get('URL'); ?>messenger/chat/<?= $user->user_id; ?>">

                            <?= $user->user_name; ?>

                        </a>

                    </div>

                <?php } ?>

            </div>

            <!-- CHAT -->
            <div style="flex:1;">

                <h3>Chat</h3>

                <?php foreach ($this->messages as $message) { ?>

                    <div style="
                        margin-bottom:15px;
                        padding:10px;
                        border:1px solid #ccc;
                    ">

                        <?= $message->message_content; ?> 

                    </div>

                <?php } ?>

            </div>

        </div>

    </div>

</div>