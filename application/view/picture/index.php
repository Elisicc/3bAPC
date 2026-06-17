<div class="container">


<form action="<?php echo Config::get('URL'); ?>picture/upload_action"
      method="post"
      enctype="multipart/form-data">

    <input type="file" name="picture_file" required>
    <input type="submit" value="Upload">
</form>

<style>
    .picture-item-container {
        position: relative;
        display: inline-block;
        border: 2px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
    }

    .picture-item-container:hover .delete-btn {
        display: block;
    }

    .delete-btn {
        display: none;
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: #f44336;
        color: white;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        font-size: 18px;
        cursor: pointer;
        line-height: 1;
        z-index: 10;
    }

    .delete-btn:hover {
        background-color: #d32f2f;
    }
</style>

<div class="container">
    <h1>PictureController/index</h1>
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

        <h3>Picture Upload and removal</h3>
        <p>
            Test Text for my Picture site
        </p>
    </div>

    <?php if (!empty($this->pictures)) : ?>
        <div class="picture-gallery" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; padding: 20px;">
            <?php foreach ($this->pictures as $picture) : ?>
                <div class="picture-item-container">
                    <a href="<?php echo Config::get('URL'); ?>picture/show/<?php echo $picture->picture_id; ?>" target="_blank">
                        <img src="<?php echo Config::get('URL'); ?>picture/show/<?php echo $picture->picture_id; ?>"
                             alt="<?= htmlentities($picture->original_filename); ?>"
                             style="width: 100%; height: 200px; object-fit: cover; display: block;">
                    </a>
                    <button class="delete-btn" onclick="if(confirm('Bild wirklich löschen?')) window.location.href='<?php echo Config::get('URL'); ?>picture/delete/<?php echo $picture->picture_id; ?>'">
                        ✕
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <p>Du hast noch keine Bilder hochgeladen.</p>
    <?php endif; ?>
</div>

