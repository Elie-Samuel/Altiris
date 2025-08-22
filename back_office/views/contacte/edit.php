<?php
require_once '../../components/header.php';
require_once dirname(__DIR__, 2) . '/controllers/ContacteController.php';

$controller = new ContacteController();
$error = null;

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    error_log("edit.php : ID invalide ($id)");
    header("Location: /Altiri/rendez-vous?error=" . urlencode("ID invalide"));
    exit;
}

$contact = $controller->edit($id);
if ($contact === false || !is_array($contact)) {
    error_log("edit.php : Contact introuvable pour ID $id");
    header("Location: /Altiri/rendez-vous?error=" . urlencode("Contact introuvable"));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->edit($id);
    if (is_string($result)) {
        error_log("edit.php : Erreur soumission formulaire ID $id : $result");
        $error = $result;
    }
}

$success = isset($_GET['success']) && $_GET['success'] == 1;
$successMessage = isset($_GET['message']) ? urldecode($_GET['message']) : null;
?>

<style>
.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
}

h2 {
    font-size: 1.8rem;
    font-weight: 600;
    color: #1c2526;
    margin-bottom: 20px;
}

.form-label {
    font-weight: 500;
    color: #1c2526;
    font-size: 0.95rem;
}

.form-control, .form-select {
    border-radius: 6px;
    border: 1px solid #e4e6eb;
    padding: 10px;
    font-size: 0.9rem;
}

.form-control:disabled {
    background-color: #f0f2f5;
    color: #65676b;
}

.btn-primary {
    background-color: #1877f2;
    border-color: #1877f2;
    font-size: 0.9rem;
    padding: 8px 16px;
    border-radius: 6px;
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

.btn-secondary {
    background-color: #e4e6eb;
    border-color: #e4e6eb;
    color: #1c2526;
    font-size: 0.9rem;
    padding: 8px 16px;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.btn-secondary:hover {
    background-color: #d8dade;
    border-color: #d8dade;
    transform: translateY(-1px);
}

.btn-secondary:active {
    transform: translateY(0);
}

.alert {
    border-radius: 6px;
    font-size: 0.9rem;
}

.invalid-feedback {
    font-size: 0.85rem;
}

.toast-container {
    z-index: 1050;
}

@media (max-width: 576px) {
    .container {
        padding: 15px;
    }
    h2 {
        font-size: 1.5rem;
    }
    .btn {
        font-size: 0.8rem;
        padding: 6px 12px;
    }
}
</style>

<div class="container mt-4">
    <h2>Répondre à la demande #<?= htmlspecialchars($contact['id_cont'] ?? 'Invalide') ?></h2>

    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success" role="alert"><?= htmlspecialchars($successMessage ?? 'Réponse envoyée avec succès !') ?></div>
    <?php endif; ?>

    <form method="POST" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" class="form-control" id="name" value="<?= htmlspecialchars($contact['name'] ?? '') ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($contact['email'] ?? '') ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="tel" class="form-label">Téléphone</label>
            <input type="text" class="form-control" id="tel" value="<?= htmlspecialchars($contact['tel'] ?? '') ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="subject" class="form-label">Sujet</label>
            <input type="text" class="form-control" id="subject" value="<?= htmlspecialchars($contact['subject'] ?? '') ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="text" class="form-label">Message</label>
            <textarea class="form-control" id="text" rows="4" disabled><?= htmlspecialchars($contact['text'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <input type="text" class="form-control" id="adresse" value="<?= htmlspecialchars($contact['adresse'] ?? '') ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="response" class="form-label">Réponse</label>
            <select name="response" id="response" class="form-select" required aria-describedby="responseHelp">
                <option value="" <?= !isset($contact['response']) || empty($contact['response']) ? 'selected' : '' ?>>Choisir une réponse</option>
                <option value="accepté" <?= isset($contact['response']) && $contact['response'] === 'accepté' ? 'selected' : '' ?>>Accepter</option>
                <option value="refusé" <?= isset($contact['response']) && $contact['response'] === 'refusé' ? 'selected' : '' ?>>Refuser</option>
            </select>
            <div id="responseHelp" class="form-text">Sélectionnez si la demande est acceptée ou refusée.</div>
            <div class="invalid-feedback">Veuillez sélectionner une réponse.</div>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-send-fill"></i> Envoyer la réponse</button>
            <a href="/Altiris/rendez-vous" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script>
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    document.body.insertAdjacentHTML('beforeend', 
                        '<div class="toast-container position-fixed bottom-0 end-0 p-3">' +
                        '<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="2000">' +
                        '<div class="toast-body">Réponse envoyée avec succès.</div></div></div>'
                    );
                    setTimeout(() => {
                        document.querySelector('.toast').classList.add('show');
                    }, 100);
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>