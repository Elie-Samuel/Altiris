<?php require_once 'partials/header.php'; ?>

<div class="container py-5">
    <h1 class="text-center mb-5 reveal">Prendre rendez-vous</h1>
    
    <div class="row">
        <?php foreach ($members as $member): ?>
        <div class="col-md-4 mb-4 reveal">
            <div class="card h-100">
                <div class="member-photo card-img-top" 
                     style="background-image: url('<?= BASE_URL ?>assets/images/team/<?= htmlspecialchars($member['photo']) ?>'); height: 250px; background-size: cover;">
                </div>
                <div class="card-body">
                    <h3><?= htmlspecialchars($member['prenom'] . ' ' . $member['nom']) ?></h3>
                    <p class="text-primary"><?= htmlspecialchars($member['role']) ?></p>
                    <div class="contact-info">
                        <p><i class="fas fa-phone me-2"></i> <?= htmlspecialchars($member['Tel']) ?></p>
                        <p><i class="fas fa-envelope me-2"></i> <?= htmlspecialchars($member['email']) ?></p>
                    </div>
                    <button class="btn btn-primary mt-3" 
                            data-bs-toggle="modal" 
                            data-bs-target="#appointmentModal"
                            data-email="<?= htmlspecialchars($member['email']) ?>">
                        Prendre rendez-vous
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Demande de rendez-vous</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="appointmentForm" action="<?= BASE_URL ?>process_rendezvous" method="POST">
                    <input type="hidden" name="member_email" id="memberEmail" value="">
                    
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="date" required min="<?= date('Y-m-d') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Heure</label>
                        <input type="time" class="form-control" name="heure" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Lieu</label>
                        <select class="form-select" name="lieu_rend" required>
                            <option value="">Sélectionnez...</option>
                            <option value="Bureau">Dans nos bureaux</option>
                            <option value="Visio">Visio-conférence</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Objet</label>
                        <textarea class="form-control" name="but_rend" required rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Votre nom</label>
                        <input type="text" class="form-control" name="client_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Votre email</label>
                        <input type="email" class="form-control" name="client_email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Votre téléphone</label>
                        <input type="tel" class="form-control" name="client_phone" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Envoyer la demande</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>