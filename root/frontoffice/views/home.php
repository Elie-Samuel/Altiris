<link href="/Altiris/root/frontoffice/css/announcements.css" rel="stylesheet">
<!-- <link href="/Altiris/root/frontoffice/css/testimonials.css" rel="stylesheet"> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>



<!-- Section Slider Annonces -->
<?php if (!empty($announcements)): ?>
<div class="announcements-slider">
    <?php foreach ($announcements as $announcement): ?>
        <div class="announcement-item">
            <?php if (!empty($announcement['image'])): ?>
                <img src="<?= $announcement['image'] ?>" alt="Annonce">
            <?php endif; ?>
            <div class="announcement-text">
                <?php foreach ($announcement['lines'] as $line): ?>
                    <p><?= htmlspecialchars($line) ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div class="slide-indicators">
    <?php foreach ($announcements as $index => $announcement): ?>
        <div class="slide-indicator <?= $index === 0 ? 'active' : '' ?>" 
             data-index="<?= $index ?>"></div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des annonces
    gsap.from('.announcement-line', {
        opacity: 0,
        y: 50,
        duration: 1,
        stagger: 0.3,
        delay: 0.5
    });

    // Slider annonces
    const slider = document.querySelector('.announcements-slider');
    if (slider) {
        let currentIndex = 0;
        const items = slider.querySelectorAll('.announcement-item');
        const indicators = document.querySelectorAll('.slide-indicator');
        const totalItems = items.length;

        function updateSlider() {
            // Masquer tous les éléments
            items.forEach(item => item.style.display = 'none');
            indicators.forEach(ind => ind.classList.remove('active'));
            
            // Afficher l'élément courant
            items[currentIndex].style.display = 'block';
            indicators[currentIndex].classList.add('active');
        }

        function nextItem() {
            currentIndex = (currentIndex + 1) % totalItems;
            updateSlider();
        }

        // Gestion des indicateurs
        indicators.forEach(indicator => {
            indicator.addEventListener('click', () => {
                currentIndex = parseInt(indicator.dataset.index);
                updateSlider();
            });
        });

        // Auto-scroll
        const interval = setInterval(nextItem, 5000);
        
        // Pause au survol
        slider.addEventListener('mouseenter', () => clearInterval(interval));
        slider.addEventListener('mouseleave', () => {
            clearInterval(interval);
            interval = setInterval(nextItem, 5000);
        });

        updateSlider();
    }
});
</script>




<!-- Section Services -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2>Nos Services Phares</h2>
                <p class="lead">Découvrez nos solutions clés pour votre entreprise</p>
            </div>
            
            <div class="row g-4">
                <?php if (!empty($services)): ?>
                    <?php 
                    // Définir les couleurs pour chaque service (sans icônes)
                    $serviceColors = [
                        'primary',
                        'success',
                        'info'
                    ];
                    $index = 0;
                    ?>
                    
                    <?php foreach ($services as $service): ?>
                        <?php 
                        $color = $serviceColors[$index % count($serviceColors)];
                        $index++;
                        ?>
                        
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <h4><?= htmlspecialchars($service['titre'] ?? 'Service') ?></h4>
                                    <?php if (!empty($service['image'])): ?>
                                        <img src="data:image/jpeg;base64,<?= base64_encode($service['image']) ?>" class="img-fluid mb-3" alt="Image service">
                                    <?php endif; ?>
                                    <p class="text-muted"><?= htmlspecialchars($service['texte'] ?? 'Description du service') ?></p>
                                    <a href="#" class="btn btn-outline-<?= $color ?> btn-sm">En savoir plus</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <div class="alert alert-info">Aucun service disponible pour le moment.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

<!-- Section Contact rapide -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="mb-3">Prêt à transformer votre entreprise ?</h3>
                <p class="mb-md-0">Contactez-nous dès aujourd'hui pour discuter de vos besoins.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="/Altiris/root/frontoffice/contact" class="btn btn-light btn-lg">Nous contacter</a>
            </div>
        </div>
    </div>
</section>


<!-- Section Actualités -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Dernières Actualités</h2>
            <p class="lead">Restez informé de nos dernières nouveautés</p>
        </div>
        
        <div class="row g-4">
            <?php if (!empty($actualites)): ?>
                <?php foreach ($actualites as $actualite): ?>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <?php if (!empty($actualite['image'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($actualite['image']) ?>" 
                                     class="card-img-top" 
                                     alt="Actualité <?= $actualite['id'] ?>"
                                     style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="far fa-newspaper fa-4x"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <div class="d-flex mb-2">
                                    <small class="text-muted">
                                        <i class="far fa-calendar me-1"></i> 
                                        <?= htmlspecialchars($actualite['date_formatee']) ?>
                                    </small>
                                </div>
                                <h5 class="card-title"><?= htmlspecialchars($actualite['titre']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars(mb_substr($actualite['texte'], 0, 100)) ?><?= (mb_strlen($actualite['texte']) > 100) ? '...' : '' ?></p>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <a href="/Altiris/root/frontoffice/actualite?id=<?= $actualite['id'] ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                   Lire la suite
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Problème de chargement des actualités. Veuillez réessayer plus tard.
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="/Altiris/root/frontoffice/actualites" class="btn btn-outline-primary">
                <i class="fas fa-newspaper me-2"></i> Voir toutes les actualités
            </a>
        </div>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const slides = document.querySelectorAll('.db-slide');
  const dots = document.querySelectorAll('.db-dot');
  const prevBtn = document.querySelector('.db-prev-btn');
  const nextBtn = document.querySelector('.db-next-btn');
  let currentIndex = 0;

  function updateSlider() {
    slides.forEach((slide, index) => {
      slide.style.transform = `translateX(-${currentIndex * 100}%)`;
      slide.classList.toggle('active', index === currentIndex);
    });
    
    dots.forEach((dot, index) => {
      dot.classList.toggle('active', index === currentIndex);
    });
  }

  function nextSlide() {
    currentIndex = (currentIndex + 1) % slides.length;
    updateSlider();
  }

  function prevSlide() {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    updateSlider();
  }

  // Boutons navigation
  if (nextBtn) nextBtn.addEventListener('click', nextSlide);
  if (prevBtn) prevBtn.addEventListener('click', prevSlide);

  // Dots navigation
  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      currentIndex = index;
      updateSlider();
    });
  });

  // Auto-play (optionnel)
  const autoplayInterval = setInterval(nextSlide, 7000);
  
  // Pause on hover
  const slider = document.querySelector('.db-slider-container');
  if (slider) {
    slider.addEventListener('mouseenter', () => clearInterval(autoplayInterval));
    slider.addEventListener('mouseleave', () => {
      clearInterval(autoplayInterval);
      autoplayInterval = setInterval(nextSlide, 7000);
    });
  }

  // Keyboard navigation
  document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowRight') nextSlide();
    if (e.key === 'ArrowLeft') prevSlide();
  });
});
</script>