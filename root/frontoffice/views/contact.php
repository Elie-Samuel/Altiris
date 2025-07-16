<?php
require_once __DIR__.'/partials/header.php';
?>

<link href="/Altiris/root/frontoffice/css/contact.css" rel="stylesheet">
<link href="/Altiris/Assets/fontawesome/css/all.min.css" rel="stylesheet">

<body>
    <div class="contact-container">
        <h1>CONTACT US</h1>
       
        <section class="contact-section">
            <div class="contact-info">
                <h2><i class="fas fa-envelope-open-text"></i> Get In Touch</h2>
                <p class="contact-description">
                    Seid ut perspektivs under ennista inte matus error sk voluptatem, accusantium odioremquei laudiantium, totum nem aperiam.
                </p>
                
                <div class="addresses"> 
                    <?php if (!empty($contactInfo)): ?>
                        <div class="address-card">
                            <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($contactInfo['adresse'] ?? 'Non spécifié') ?></p>
                            <p><i class="fas fa-phone"></i> <?= htmlspecialchars($contactInfo['phonne'] ?? 'Non spécifié') ?></p>
                            <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($contactInfo['mail'] ?? 'Non spécifié') ?></p>
                            <?php if (!empty($contactInfo['lien_facebook'])): ?>
                                <p><i class="fab fa-facebook"></i> <a href="<?= htmlspecialchars($contactInfo['lien_facebook']) ?>" target="_blank">Facebook</a></p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">Aucune information de contact disponible</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="contact-form">
                <h3>Nous contacter</h3>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form method="POST" action="/Altiris/root/?page=contact&action=submit">
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" id="name" name="name" placeholder="Votre nom" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email*</label>
                        <input type="email" id="email" name="email" placeholder="Votre email" required
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Sujet</label>
                        <input type="text" id="subject" name="subject" placeholder="Sujet"
                            value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message*</label>
                        <textarea id="message" name="message" placeholder="Votre message" required><?= 
                            htmlspecialchars($_POST['message'] ?? '') 
                        ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <!-- Modal de confirmation -->
    <?php if (isset($success) && $success): ?>
    <div id="contactModal" class="modal" style="display:block;">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="modal-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>Message envoyé!</h3>
            <p><?= htmlspecialchars($message) ?></p>
        </div>
    </div>

    <script>
    // Gestion du modal
    const modal = document.getElementById("contactModal");
    const span = document.getElementsByClassName("close-modal")[0];

    // Fermer après 3 secondes
    setTimeout(() => {
        if (modal) modal.style.display = "none";
    }, 3000);

    // Fermer en cliquant sur la croix
    if (span) {
        span.onclick = function() {
            modal.style.display = "none";
        }
    }

    // Fermer en cliquant à l'extérieur
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    </script>
 c
    <style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        animation: fadeIn 0.3s;
    }

    .modal-content {
        background-color: #fff;
        margin: 15% auto;
        padding: 30px;
        border-radius: 8px;
        width: 80%;
        max-width: 400px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .close-modal {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close-modal:hover {
        color: #333;
    }

    .modal-icon {
        font-size: 50px;
        color: #4CAF50;
        margin-bottom: 20px;
    }

    .modal h3 {
        color: #4CAF50;
        margin-bottom: 15px;
    }

    @keyframes fadeIn {
        from {opacity: 0;}
        to {opacity: 1;}
    }
    </style>
    <?php endif; ?>

</body>
</html>

<?php
require_once __DIR__.'/partials/footer.php';
?>