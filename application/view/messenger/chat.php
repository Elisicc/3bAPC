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

                            <img src="<?= $user->user_avatar_link; ?>" />   
                            <?= $user->user_name; ?>

                        </a>

                    </div>

                <?php } ?>

            </div>

            <!-- CHAT -->
            <div style="flex:1;">

                <h3>Chat</h3>

                <h3>Chat</h3>

                <section class="discussion">

                <?php foreach ($this->messages as $message) { ?>

                    <?php
                        if ($message->sender_id == Session::get('user_id')) {
                            $class = "sender";
                        } else {
                            $class = "recipient";
                        }
                    ?>

                    <div class="bubble <?= $class; ?>">

                        <?= $message->message_content; ?>

                    </div>

                <?php } ?>

                </section>
                <hr>

                <form action="<?= Config::get('URL'); ?>messenger/send" method="post">
                <br><br><br><br>

                    <input
                        type="hidden"
                        name="recipient_id"
                        value="<?= $this->chat_partner_id; ?>"
                    >
                    <textarea
                        name="message_content"
                        rows="4"
                        cols="60"
                    ></textarea>

                    <br><br>

                    <input
                        type="submit"
                        value="Send"
                    >
                </form>

            </div>

        </div>

    </div>

</div>