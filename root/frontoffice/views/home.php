<div class="container-fluid p-0">
    <!-- Carousel d'images -->
    <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php 
            $active = true;
            foreach ($images as $key => $imageData) {
                if ($key !== 'id') {
                    $imageBase64 = base64_encode($imageData);
                    ?>
                    <div class="carousel-item <?= $active ? 'active' : '' ?>">
                        <img src="data:image/jpeg;base64,<?= $imageBase64 ?>" class="d-block w-100" alt="<?= $key ?>">
                    </div>
                    <?php
                    $active = false;
                }
            }
            ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>