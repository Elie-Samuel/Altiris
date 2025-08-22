<?php
ob_start();
session_start();
require_once dirname(__DIR__, 2) . '/controllers/TemoignageController.php';

if (!isset($_SESSION['types']) || !in_array($_SESSION['types'], ['Super admin', 'Admin'])) {
    $_SESSION['error'] = "Accès non autorisé.";
    header("Location: /Altiris/connexion");
    exit;
}

$controller = new TemoignageController();
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id) || $id <= 0) {
    $_SESSION['error'] = "ID témoignage manquant ou invalide.";
    header("Location: /Altiris/temoignages");
    exit;
}

$temoignage = $controller->edit($id);
if (!$temoignage) {
    $_SESSION['error'] = $error ?? "Témoignage non trouvé.";
    header("Location: /Altiris/temoignages");
    exit;
}

require_once dirname(__DIR__, 2) . '/components/header.php';
?>

<link rel="stylesheet" href="/Altiris/back_office/css/temoignage.css">

<div class="page-header">
    <h2><i class="fas fa-edit"></i> Modifier le Témoignage</h2>
    <a href="/Altiris/temoignages" class="btn btn-secondary btn-lg">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<!-- Messages de feedback -->
<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<div class="form-container fade-in-up">
    <form method="POST" enctype="multipart/form-data" id="editTemoignageForm">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nom" class="form-label">
                        <i class="fas fa-user"></i> Nom complet *
                    </label>
                    <input type="text" class="form-control" id="nom" name="nom" 
                           required maxlength="100" placeholder="Nom de la personne"
                           value="<?php echo htmlspecialchars($temoignage['Nom'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="rang" class="form-label">
                        <i class="fas fa-briefcase"></i> Fonction/Rang *
                    </label>
                    <input type="text" class="form-control" id="rang" name="rang" 
                           required maxlength="100" placeholder="Directeur, Client, Partenaire..."
                           value="<?php echo htmlspecialchars($temoignage['rang'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="text_tem" class="form-label">
                <i class="fas fa-comment"></i> Contenu du témoignage *
            </label>
            <textarea class="form-control" id="text_tem" name="text_tem" rows="6" required 
                      placeholder="Saisissez le témoignage complet..."><?php echo htmlspecialchars($temoignage['text_tem'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            <small class="form-text">Décrivez l'expérience, les résultats obtenus, les points positifs...</small>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="image" class="form-label">
                        <i class="fas fa-image"></i> Photo de la personne
                    </label>
                    <input type="file" class="form-control" id="image" name="image" 
                           accept="image/jpeg,image/png,image/gif,image/webp">
                    <small class="form-text">Formats acceptés : JPG, PNG, GIF, WebP (Max: 5MB)</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Image actuelle</label>
                    <div>
                        <?php 
                        $imagePath = !empty($temoignage['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/Altiris/' . $temoignage['image']) 
                            ? '/Altiris/' . htmlspecialchars($temoignage['image'], ENT_QUOTES, 'UTF-8') 
                            : '/Altiris/Assets/Images/default_profile.jpg';
                        ?>
                        <img id="currentImage" src="<?php echo $imagePath; ?>" alt="Image actuelle" class="image-preview">
                        <img id="imagePreview" src="" alt="Nouvelle image" class="image-preview" style="display: none;">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Informations</label>
            <div style="background: rgba(255,255,255,0.05); padding: 15px; border-radius: 12px; border: 1px solid rgba(249,115,22,0.2);">
                <p style="margin: 0; color: rgba(255,255,255,0.8);">
                    <strong style="color: var(--altiris-orange);">ID :</strong> <?php echo htmlspecialchars($temoignage['id_tem'], ENT_QUOTES, 'UTF-8'); ?>
                </p>
                <?php if (isset($temoignage['date_creation'])): ?>
                <p style="margin: 5px 0 0 0; color: rgba(255,255,255,0.8);">
                    <strong style="color: var(--altiris-orange);">Créé le :</strong> 
                    <?php echo date('d/m/Y à H:i', strtotime($temoignage['date_creation'])); ?>
                </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-actions">
            <a href="/Altiris/temoignages" class="btn btn-secondary">
                <i class="fas fa-times"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
// Validation du formulaire
document.getElementById('editTemoignageForm').addEventListener('submit', function(e) {
    const nom = document.getElementById('nom').value.trim();
    const rang = document.getElementById('rang').value.trim();
    const textTem = document.getElementById('text_tem').value.trim();
    
    if (!nom || !rang || !textTem) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return false;
    }
    
    if (nom.length > 100) {
        e.preventDefault();
        alert('Le nom ne peut pas dépasser 100 caractères.');
        return false;
    }
    
    if (rang.length > 100) {
        e.preventDefault();
        alert('Le rang ne peut pas dépasser 100 caractères.');
        return false;
    }
    
    if (textTem.length < 10) {
        e.preventDefault();
        alert('Le témoignage doit contenir au moins 10 caractères.');
        return false;
    }
});

// Aperçu de la nouvelle image
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const current = document.getElementById('currentImage');
    
    if (file) {
        // Vérifier le type de fichier
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            alert('Type de fichier non autorisé. Utilisez JPG, PNG, GIF ou WebP.');
            e.target.value = '';
            return;
        }
        
        // Vérifier la taille
        if (file.size > 5 * 1024 * 1024) {
            alert('Le fichier est trop volumineux. Taille maximale : 5MB.');
            e.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            current.style.display = 'none';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
        current.style.display = 'block';
    }
});

// Compteur de caractères pour le textarea
const textarea = document.getElementById('text_tem');
const maxLength = 1000; // Limite suggérée

textarea.addEventListener('input', function() {
    const remaining = maxLength - this.value.length;
    let counter = document.getElementById('charCounter');
    
    if (!counter) {
        counter = document.createElement('small');
        counter.id = 'charCounter';
        counter.className = 'form-text';
        counter.style.float = 'right';
        this.parentNode.appendChild(counter);
    }
    
    counter.textContent = `${this.value.length}/${maxLength} caractères`;
    counter.style.color = remaining < 50 ? '#ef4444' : 'rgba(255,255,255,0.7)';
});

// Initialiser le compteur
textarea.dispatchEvent(new Event('input'));
</script>