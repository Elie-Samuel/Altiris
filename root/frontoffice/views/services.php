<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Nos Services</h2>
        <div class="row">
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 service-card">
                        <?php 
                        $imageData = $services['image'.$i];
                        $imageBase64 = base64_encode($imageData);
                        ?>
                        <img src="data:image/jpeg;base64,<?= $imageBase64 ?>" class="card-img-top" alt="Service <?= $i ?>">
                        <div class="card-body">
                            <p class="card-text"><?= htmlspecialchars($services['text'.$i]) ?></p>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>