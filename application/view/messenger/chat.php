<div class="container">

    <h1>Messenger Chat</h1>

    <div class="box">

        <div style="display:flex; gap:40px;">

            <!-- USER LIST -->
            <div style="width:200px;">

                <h3>Gruppen</h3>

                <?php if (!empty($this->groups)) : ?>
                    <?php foreach ($this->groups as $group) : ?>
                        <div style="margin-bottom:15px;">
                            <a href="<?= Config::get('URL'); ?>messenger/chatgroup/<?= $group->group_id; ?>">
                                <?= htmlspecialchars($group->group_name); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Du bist in noch keinen Gruppenchats.</p>
                <?php endif; ?>

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

                <h3>
                    <?php if (isset($this->group)) {
                        echo 'Gruppenchat: ' . htmlspecialchars($this->group->group_name);
                    } else {
                        echo 'Chat';
                    } ?>
                </h3>

                <p>Hier kannst du mit deinen Freunden chatten oder sogar Chat-Gruppen erstellen und mit mehreren Freunden gleichzeitig chatten!</p>

                <button type="button" class="group-create-button" id="open-group-chat-dialog">Gruppenchat erstellen</button>

                <div class="group-dialog-overlay hidden" id="group-chat-dialog">
                    <div class="group-dialog">
                        <div class="group-dialog-header">
                            <h2>Gruppenchat erstellen</h2>
                            <button type="button" class="dialog-close" id="close-group-chat-dialog">&times;</button>
                        </div>
                        <form action="<?= Config::get('URL'); ?>messenger/createGroup" method="post">
                            <label for="group_name">Gruppenname</label>
                            <input type="text" id="group_name" name="group_name" required />

                            <p>Teilnehmer auswählen:</p>
                            <div class="group-member-list">
                                <?php foreach ($this->users as $user) { ?>
                                    <label class="group-member-item">
                                        <input type="checkbox" name="group_members[]" value="<?= $user->user_id; ?>" />
                                        <?= htmlspecialchars($user->user_name); ?>
                                    </label>
                                <?php } ?>
                            </div>

                            <div class="group-dialog-actions">
                                <button type="button" class="dialog-close" id="cancel-group-chat-dialog">Abbrechen</button>
                                <button type="submit">Gruppe erstellen</button>
                            </div>
                        </form>
                    </div>
                </div>

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

                    <?php if (isset($this->chat_group_id)) { ?>
                        <input type="hidden" name="group_id" value="<?= $this->chat_group_id; ?>">
                    <?php } else { ?>
                        <input
                            type="hidden"
                            name="recipient_id"
                            value="<?= $this->chat_partner_id; ?>"
                        >
                    <?php } ?>

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

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var dialog = document.getElementById('group-chat-dialog');
                        var openButton = document.getElementById('open-group-chat-dialog');
                        var closeButtons = document.querySelectorAll('.dialog-close');

                        function toggleDialog(show) {
                            if (show) {
                                dialog.classList.remove('hidden');
                            } else {
                                dialog.classList.add('hidden');
                            }
                        }

                        if (openButton) {
                            openButton.addEventListener('click', function() {
                                toggleDialog(true);
                            });
                        }

                        closeButtons.forEach(function(button) {
                            button.addEventListener('click', function() {
                                toggleDialog(false);
                            });
                        });

                        if (dialog) {
                            dialog.addEventListener('click', function(event) {
                                if (event.target === dialog) {
                                    toggleDialog(false);
                                }
                            });
                        }
                    });
                </script>

            </div>

        </div>

    </div>

</div>