<div class="container">


<form action="<?php echo Config::get('URL'); ?>picture/upload_action"
      method="post"
      enctype="multipart/form-data">

    <input type="file" name="picture_file" required>
    <input type="submit" value="Upload">
</form>

<style>
    .gallery {
        --size: 18em;
        --gap: 1em;
        --zoom: 1.3;

        display: grid;
        gap: var(--gap);
        grid-template-columns: repeat(auto-fit, minmax(var(--size), 1fr));
        justify-content: center;
        padding: 20px;
    }

    .gallery figure {
        position: relative;
        overflow: hidden;
        width: var(--size);
        height: var(--size);
        border: 2px solid #ddd;
        border-radius: 5px;
        margin: 0;
    }

    .gallery figure a {
        display: block;
        width: 100%;
        height: 100%;
    }

    .gallery figure:hover .delete-btn,
    .gallery figure:hover .download-btn {
        display: block;
    }

    .gallery figcaption {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        padding: 0.5em 0;
        text-align: center;
        color: white;
        background: rgba(0, 0, 0, 0.35);
        opacity: 0;
        transition: opacity 0.25s ease;
        font-size: 0.9rem;
        pointer-events: none;
    }

    .gallery figure:hover figcaption,
    .gallery figure:focus-within figcaption {
        opacity: 1;
    }

    .gallery > figure img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: grayscale(80%);
        transition: transform 0.35s ease, filter 0.35s ease;
        transform-origin: center center;
        transform: scale(1);
        display: block;
    }

    .gallery figure:hover img,
    .gallery figure:focus-within img {
        filter: grayscale(0);
        transform: scale(var(--zoom));
    }

    .delete-btn,
    .download-btn {
        display: none;
        position: absolute;
        top: 5px;
        width: 30px;
        height: 30px;
        border: none;
        border-radius: 50%;
        font-size: 18px;
        cursor: pointer;
        line-height: 1;
        z-index: 10;
    }

    .delete-btn {
        right: 5px;
        background-color: #f44336;
        color: white;
    }

    .download-btn {
        right: 45px;
        background-color: #2196f3;
        color: white;
    }

    .delete-btn:hover {
        background-color: #d32f2f;
    }

    .download-btn:hover {
        background-color: #1976d2;
    }
</style>

<div class="container">
    <h1>PictureController/index</h1>
    <div class="box">

    <?php if (!empty($this->pictures)) : ?>
        <div class="gallery">
            <?php foreach ($this->pictures as $picture) : ?>
                <figure>
                    <a href="<?php echo Config::get('URL'); ?>picture/show/<?php echo $picture->picture_id; ?>" target="_blank">
                        <img src="<?php echo Config::get('URL'); ?>picture/show/<?php echo $picture->picture_id; ?>"
                             alt="<?= htmlentities($picture->original_filename); ?>">
                    </a>
                    <figcaption><?= htmlentities($picture->original_filename); ?></figcaption>
                    <button class="download-btn" onclick="window.location.href='<?php echo Config::get('URL'); ?>picture/show/<?php echo $picture->picture_id; ?>?download=1'" title="Herunterladen">
                        ⬇
                    </button>
                    <button class="delete-btn" onclick="if(confirm('Bild wirklich löschen?')) window.location.href='<?php echo Config::get('URL'); ?>picture/delete/<?php echo $picture->picture_id; ?>'">
                        ✕
                    </button>
                </figure>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <p>Du hast noch keine Bilder hochgeladen.</p>
    <?php endif; ?>
</div>

