<div class="container">


<form action="<?php echo Config::get('URL'); ?>picture/upload_action"
      method="post"
      enctype="multipart/form-data">

    <input type="file" name="picture_file" required>
    <input type="submit" value="Upload">
</form>

<form id="publish-form" action="" method="post">
    <input id="publish-button" type="submit" value="Publish" disabled>
</form>

<script>
    const pictures = document.querySelectorAll('.selectable-picture');
    const publishForm = document.getElementById('publish-form');
    const publishButton = document.getElementById('publish-button');

    pictures.forEach(function (picture) {
        picture.addEventListener('click', function () {
            const pictureId = picture.dataset.pictureId;

            publishForm.action = '<?php echo Config::get('URL'); ?>picture/publish/' + pictureId;
            publishButton.disabled = false;

            pictures.forEach(function (p) {
                p.style.outline = 'none';
            });

            picture.style.outline = '3px solid #4CAF50';
        });
    });
</script>

<div class="container">
    <h1>PictureController/index</h1>
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

        <h3>Picture Upload and removal</h3>
        <p>
            Test Text for my Picture site
        <p>
    </div>
</div>
