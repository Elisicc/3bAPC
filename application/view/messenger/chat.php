<div class="container">

    <h1>Messenger Chat</h1>

    <div class="box">

        <div style="display:flex; gap:40px;">

            <!-- USER LIST -->
            <div style="width:200px;">

                <h3>Users</h3>

                <?php foreach ($this->users as $user) { ?>

                    <div style="margin-bottom:15px;">

                        <a href="<?= Config::get('URL'); ?>messenger/chat/<?= $user->user_id; ?>">

                            <img
                                src="<?= $user->user_avatar_link; ?>"
                                style="width:70px; vertical-align:middle;"
                            >

                            <?= $user->user_name; ?>

                        </a>

                    </div>

                <?php } ?>

            </div>

            <!-- CHAT -->

            <div style="flex:1;">

                <h3>Chat</h3>

                <section class="discussion">

                    <?php foreach ($this->messages as $index => $message) {

                        $previousMessage = isset($this->messages[$index - 1]) ? $this->messages[$index - 1] : null;
                        $nextMessage = isset($this->messages[$index + 1]) ? $this->messages[$index + 1] : null;

                        $class = $message->sender_id == Session::get('user_id') ? 'sender' : 'recipient';

                        $sameSenderAsPrevious = $previousMessage && $previousMessage->sender_id == $message->sender_id;
                        $sameSenderAsNext = $nextMessage && $nextMessage->sender_id == $message->sender_id;

                        $groupClass = '';
                        if (!$sameSenderAsPrevious && $sameSenderAsNext) {
                            $groupClass = ' first';
                        } elseif ($sameSenderAsPrevious && $sameSenderAsNext) {
                            $groupClass = ' middle';
                        } elseif ($sameSenderAsPrevious && !$sameSenderAsNext) {
                            $groupClass = ' last';
                        }

                    ?>

                        <div class="bubble <?= $class . $groupClass; ?>">

                            <?= htmlspecialchars($message->message_content); ?>

                        </div>

                    <?php } ?>

                </section>

                <hr>

                <form action="<?= Config::get('URL'); ?>messenger/send" method="post">

                    <input
                        type="hidden"
                        name="recipient_id"
                        value="<?= $this->chat_partner_id; ?>"
                    >

                    <textarea
                        name="message_content"
                        rows="5"
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