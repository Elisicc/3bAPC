<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#messenger-table').DataTable();
});
</script>

<link rel="stylesheet"
href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" />


<div class="container">
    <h1>Messenger</h1>

    <div class="box">

        <h3>Gruppen</h3>

        <?php if (!empty($this->groups)) : ?>
            <table class="overview-table">
                <thead>
                    <tr>
                        <td>Gruppenname</td>
                        <td>Open Chat</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->groups as $group) : ?>
                        <tr>
                            <td><?= htmlspecialchars($group->group_name); ?></td>
                            <td>
                                <a href="<?= Config::get('URL'); ?>messenger/chatgroup/<?= $group->group_id; ?>">
                                    Open
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Du bist in noch keinen Gruppenchats.</p>
        <?php endif; ?>

        <h3>Select a user to chat with</h3>

        <table class="overview-table">

            <thead>
            <tr>
                <td>Avatar</td>
                <td>Username</td>
                <td>New Messages</td>
                <td>Open Chat</td>
            </tr>
            </thead>

        <table id="messenger-table" class="overview-table">

        <thead>

        <tr>
            <td>Avatar</td>
            <td>Username</td>
            <td>New Messages</td>
            <td>Open Chat</td>
        </tr>
        </thead>
            <tbody>
                <?php foreach ($this->users as $user) { ?>
                    <tr>
                        <td class="avatar">
                            <?php if (isset($user->user_avatar_link)) { ?>
                                <img src="<?= $user->user_avatar_link; ?>" />
                            <?php } ?>
                        </td>
                        <td>
                            <?= $user->user_name; ?>
                        </td>
                        <td>
                            <?= $user->unread_messages; ?>
                        </td>
                        <td>
                            <a href="<?= Config::get('URL'); ?>messenger/chat/<?= $user->user_id; ?>">
                                Open
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </table>

    </div>
</div>