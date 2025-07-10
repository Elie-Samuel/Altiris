<link href="/Altiris/root/frontoffice/css/style.css" rel="stylesheet">

<!-- Section Témoignages version Base de Données -->
<div class="database-testimonials">
  <div class="db-header">
    <h1><i class="fas fa-database"></i> Base de données : <?= DB_NAME ?></h1>
    <h2><i class="fas fa-table"></i> Table : annonce</h2>
  </div>

  <div class="db-slider-container">
    <?php if (!empty($testimonials)): ?>
      <?php foreach ($testimonials as $key => $testimonial): ?>
        <div class="db-slide <?= $key === 0 ? 'active' : '' ?>">
          <div class="db-slide-image">
            <?php if (!empty($testimonial['image'])): ?>
              <img src="data:image/jpeg;base64,<?= base64_encode($testimonial['image']) ?>" 
                   alt="Annonce <?= $testimonial['id'] ?>">
            <?php else: ?>
              <div class="db-default-avatar">
                <i class="fas fa-user-tie"></i>
              </div>
            <?php endif; ?>
          </div>
          <div class="db-slide-content">
            <div class="db-record-meta">
              <span class="db-record-id">ID: <?= $testimonial['id'] ?></span>
              <div class="db-rating">
                <?php for ($i = 0; $i < 5; $i++): ?>
                  <i class="fas fa-star"></i>
                <?php endfor; ?>
              </div>
            </div>
            <div class="db-record-content">
              <p>"<?= htmlspecialchars($testimonial['texte'] ?? '') ?>"</p>
            </div>
            <div class="db-record-footer">
              <span class="db-client-type">Annonce vérifiée</span>
              <span class="db-date"><?= date('Y-m-d') ?></span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="db-no-records">
        <i class="fas fa-exclamation-circle"></i>
        <p>Aucun enregistrement trouvé dans la table</p>
      </div>
    <?php endif; ?>
  </div>

  <?php if (count($testimonials) > 1): ?>
    <div class="db-controls">
      <button class="db-prev-btn"><i class="fas fa-chevron-left"></i> Précédent</button>
      <button class="db-next-btn">Suivant <i class="fas fa-chevron-right"></i></button>
    </div>

    <div class="db-pagination">
      <?php for ($i = 0; $i < count($testimonials); $i++): ?>
        <span class="db-dot <?= $i === 0 ? 'active' : '' ?>"></span>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</div>


<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Nos Services Phares</h2>
            <p class="lead">Découvrez nos solutions clés pour votre entreprise</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="icon-square bg-primary bg-gradient text-white rounded-circle mb-3 mx-auto" style="width: 60px; height: 60px; line-height: 60px;">
                            <i class="fas fa-cog fa-lg"></i>
                        </div>
                        <h4>Solution Technique</h4>
                        <p class="text-muted">Des solutions techniques sur mesure pour répondre à vos besoins spécifiques.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">En savoir plus</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="icon-square bg-success bg-gradient text-white rounded-circle mb-3 mx-auto" style="width: 60px; height: 60px; line-height: 60px;">
                            <i class="fas fa-chart-line fa-lg"></i>
                        </div>
                        <h4>Analyse de Données</h4>
                        <p class="text-muted">Transformez vos données en informations exploitables pour votre business.</p>
                        <a href="#" class="btn btn-outline-success btn-sm">En savoir plus</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="icon-square bg-info bg-gradient text-white rounded-circle mb-3 mx-auto" style="width: 60px; height: 60px; line-height: 60px;">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                        <h4>Formation</h4>
                        <p class="text-muted">Formez vos équipes aux dernières technologies et méthodologies.</p>
                        <a href="#" class="btn btn-outline-info btn-sm">En savoir plus</a>
                    </div>
                </div>
            </div>
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
                                     alt="Actualité <?= $actualite['id'] ?>">
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
                                        <?= $actualite['date_formatee'] ?? 'Date non disponible' ?>
                                    </small>
                                </div>
                                <h5 class="card-title"><?= htmlspecialchars($actualite['titre'] ?? 'Titre non disponible') ?></h5>
                                <p class="card-text"><?= htmlspecialchars(substr($actualite['texte'], 0, 100) . '...' ?? 'Description non disponible') ?></p>
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
                    <div class="alert alert-info">Aucune actualité disponible pour le moment.</div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="/Altiris/root/frontoffice/actualites" class="btn btn-outline-primary">Voir toutes les actualités</a>
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