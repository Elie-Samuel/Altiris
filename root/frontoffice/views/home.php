
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Ils nous font confiance</h2>
            <p class="lead">Découvrez ce que nous sommes capables</p>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($testimonials as $key => $testimonial): ?>
                            <div class="carousel-item <?= $key === 0 ? 'active' : '' ?>">
                                <div class="card border-0 shadow-none">
                                    <div class="card-body text-center p-4">
                                        <?php if (!empty($testimonial['image'])): ?>
                                            <img src="data:image/jpeg;base64,<?= base64_encode($testimonial['image']) ?>" 
                                                 class="rounded-circle mb-3" width="80" alt="Client">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mb-3 mx-auto" style="width:80px;height:80px;">
                                                <i class="fas fa-user fa-2x"></i>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <p class="lead fst-italic">"<?= htmlspecialchars($testimonial['text']) ?>"</p>
                                        <div class="text-warning mb-2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <!-- <h5 class="mb-1">Client satisfait</h5>
                                        <p class="text-muted">Nos clients</p> -->
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark rounded-circle"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-dark rounded-circle"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>


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
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="/Altiris/Assets/images/news1.jpg" class="card-img-top" alt="Actualité 1">
                    <div class="card-body">
                        <div class="d-flex mb-2">
                            <small class="text-muted"><i class="far fa-calendar me-1"></i> 15 Juillet 2023</small>
                        </div>
                        <h5 class="card-title">Lancement de notre nouvelle plateforme</h5>
                        <p class="card-text">Découvrez notre nouvelle solution tout-en-un pour la gestion d'entreprise.</p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <a href="#" class="btn btn-sm btn-outline-primary">Lire la suite</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="/Altiris/Assets/images/news2.jpg" class="card-img-top" alt="Actualité 2">
                    <div class="card-body">
                        <div class="d-flex mb-2">
                            <small class="text-muted"><i class="far fa-calendar me-1"></i> 28 Juin 2023</small>
                        </div>
                        <h5 class="card-title">Ateliers de formation gratuits</h5>
                        <p class="card-text">Inscrivez-vous à nos prochains ateliers de formation sur les nouvelles technologies.</p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <a href="#" class="btn btn-sm btn-outline-primary">Lire la suite</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="/Altiris/Assets/images/news3.jpg" class="card-img-top" alt="Actualité 3">
                    <div class="card-body">
                        <div class="d-flex mb-2">
                            <small class="text-muted"><i class="far fa-calendar me-1"></i> 10 Juin 2023</small>
                        </div>
                        <h5 class="card-title">Partenariat stratégique</h5>
                        <p class="card-text">Nous sommes fiers d'annoncer notre nouveau partenariat avec TechSolutions.</p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <a href="#" class="btn btn-sm btn-outline-primary">Lire la suite</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="/Altiris/root/frontoffice/actualites" class="btn btn-outline-primary">Voir toutes les actualités</a>
        </div>
    </div>
</section>