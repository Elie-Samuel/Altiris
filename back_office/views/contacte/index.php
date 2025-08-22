<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ContacteController.php';

$controller = new ContacteController();
$contacts = $controller->index();
?>

<style>
/* Styles inspirés de Facebook */
.container {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
}

h2 {
    font-size: 1.8rem;
    font-weight: 600;
    color: #1c2526;
}

.badge.rounded-pill {
    font-size: 1rem;
    padding: 8px 12px;
    background-color: #1877f2;
    transition: all 0.3s ease;
}

.badge.rounded-pill:hover {
    transform: scale(1.1);
}

.list-group-item {
    border-radius: 8px;
    margin-bottom: 8px;
    padding: 15px;
    background-color: #fff;
    border: 1px solid #e4e6eb;
    transition: background-color 0.2s ease;
}

.list-group-item.fw-bold {
    background-color: #f0f2f5;
    border-left: 4px solid #1877f2;
}

.list-group-item:hover {
    background-color: #f6f7f9;
}

.list-group-item h5 {
    font-size: 1.1rem;
    margin-bottom: 5px;
    color: #1c2526;
}

.list-group-item p {
    font-size: 0.95rem;
    color: #4b5e6d;
    margin-bottom: 5px;
}

.list-group-item small {
    font-size: 0.85rem;
    color: #65676b;
}

.badge.bg-warning {
    color: #fff;
    background-color: #f7b928 !important;
}

.badge.bg-success {
    color: #fff;
    background-color: #36a420 !important;
}

.btn-primary {
    background-color: #1877f2;
    border-color: #1877f2;
    font-size: 0.9rem;
    padding: 6px 12px;
    transition: all 0.2s ease;
}

.btn-primary:hover {
    background-color: #165ece;
    border-color: #165ece;
    transform: translateY(-1px);
}

.btn-primary:active {
    transform: translateY(0);
}

.toast-container {
    z-index: 1050;
}

@media (max-width: 576px) {
    .list-group-item {
        padding: 10px;
    }
    h5 {
        font-size: 1rem;
    }
    .btn-primary {
        font-size: 0.8rem;
        padding: 5px 10px;
    }
}
</style>

<div class="container mt-4">
    <h2 class="mb-3">Boîte de réception</h2>
    <!-- Compteur de nouveaux messages -->
    <div class="mb-4">
        <span class="badge rounded-pill bg-primary" id="new-messages-count">
            <?= $controller->countNewMessages() ?>
        </span> nouveau(x) message(s)
    </div>

    <!-- Liste des messages -->
    <div class="list-group">
        <?php if (empty($contacts)): ?>
            <div class="list-group-item text-center">Aucune demande trouvée</div>
        <?php else: ?>
            <?php foreach ($contacts as $contact): ?>
                <div class="list-group-item list-group-item-action <?= $contact['status'] === 'nouveau' ? 'fw-bold' : '' ?>">
                    <div class="d-flex w-100 justify-content-between">
                        <div>
                            <h5 class="mb-1"><?= htmlspecialchars($contact['name'] ?? '') ?></h5>
                            <p class="mb-1"><?= htmlspecialchars($contact['subject'] ?? '') ?></p>
                            <small class="text-muted"><?= htmlspecialchars($contact['text'] ?? '') ?></small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge ms-2 <?= $contact['status'] === 'nouveau' ? 'bg-warning' : 'bg-success' ?>">
                                <?= htmlspecialchars(ucfirst($contact['status'] ?? '')) ?>
                            </span>
                            <a href="/altiris/rendez-vous/repondre/<?= $contact['id_cont'] ?>" 
                               class="btn btn-sm btn-primary ms-2 mark-as-read" 
                               data-id="<?= $contact['id_cont'] ?>" 
                               aria-label="Répondre et marquer comme lu pour <?= htmlspecialchars($contact['subject']) ?>">
                                Répondre
                            </a>
                        </div>
                    </div>
                    <small class="text-muted">
                        Email: <?= htmlspecialchars($contact['email'] ?? '') ?> | 
                        Téléphone: <?= htmlspecialchars($contact['tel'] ?? '') ?> | 
                        Adresse: <?= htmlspecialchars($contact['adresse'] ?? '') ?> | 
                        Réponse: <?= htmlspecialchars($contact['response'] ?? 'Non répondu') ?> | 
                        Date: <?= htmlspecialchars($contact['date_creation'] ?? '') ?>
                    </small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Script jQuery pour gérer le clic sur "Répondre" -->
<!-- ... Autre code de index.php inchangé ... -->
<script>
$(document).ready(function() {
    $('.mark-as-read').on('click', function(e) {
        e.preventDefault();
        var $button = $(this);
        var contactId = $button.data('id');
        var $item = $button.closest('.list-group-item');
        var $badge = $item.find('.badge');
        var $countBadge = $('#new-messages-count');
        var href = $button.attr('href');
        var ajaxUrl = '/Altiris/back_office/api/new-messages.php';

        if ($badge.hasClass('bg-success')) {
            console.log('Message déjà lu pour ID : ' + contactId);
            window.location.href = href;
            return;
        }

        console.log('Envoi AJAX pour marquer comme lu, ID : ' + contactId + ', URL : ' + ajaxUrl);
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Chargement...');

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action: 'mark_read',
                id: contactId
            },
            dataType: 'json',
            success: function(response) {
                console.log('Réponse AJAX : ', response);
                if (response.success) {
                    $badge.removeClass('bg-warning').addClass('bg-success').text('Lu');
                    $item.removeClass('fw-bold');
                    $countBadge.text(response.count);
                    $('body').append('<div class="toast-container position-fixed bottom-0 end-0 p-3"><div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="2000"><div class="toast-body">Message marqué comme lu.</div></div></div>');
                    $('.toast').toast('show');
                    document.dispatchEvent(new CustomEvent('messageMarkedAsRead'));
                    setTimeout(function() {
                        window.location.href = href;
                    }, 500);
                } else {
                    console.error('Erreur AJAX : ' + response.message);
                    alert('Erreur : ' + response.message);
                    window.location.href = href;
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur AJAX : Status=' + status + ', Error=' + error + ', URL=' + ajaxUrl + ', Status Code=' + xhr.status + ', Response=' + xhr.responseText);
                alert('Erreur de connexion au serveur. Vérifiez la console pour plus de détails.');
                window.location.href = href;
            },
            complete: function() {
                $button.prop('disabled', false).html('Répondre');
            }
        });
    });
});
</script>