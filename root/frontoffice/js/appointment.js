// altiris/root/frontoffice/js/appointment.js
// Gestion des rendez-vous côté client
document.addEventListener('DOMContentLoaded', function() {
    const appointmentForm = document.getElementById('appointmentForm');
    
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Récupérer les données du formulaire
            const formData = new FormData(this);
            formData.append('action', 'book_appointment');
            
            // Afficher un indicateur de chargement
            const submitBtn = this.querySelector('.btn-submit');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Envoi en cours...';
            submitBtn.disabled = true;
            
            // Envoyer la requête AJAX
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Succès
                    showNotification('Rendez-vous demandé avec succès!', 'success');
                    
                    // Fermer le modal
                    const modal = document.getElementById('appointmentModal');
                    if (modal) {
                        modal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }
                    
                    // Réinitialiser le formulaire
                    appointmentForm.reset();
                } else {
                    // Erreur
                    showNotification(data.message || 'Une erreur est survenue', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showNotification('Erreur de communication avec le serveur', 'error');
            })
            .finally(() => {
                // Restaurer le bouton
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});

// Fonction pour afficher les notifications
function showNotification(message, type = 'info') {
    // Créer l'élément de notification
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close">&times;</button>
        </div>
    `;

    
    // Ajouter les styles si ils n'existent pas déjà
    if (!document.getElementById('notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                min-width: 300px;
                padding: 15px 20px;
                border-radius: 5px;
                color: white;
                z-index: 1000;
                animation: slideInRight 0.3s ease-out;
            }
            
            .notification-success {
                background-color: #28a745;
            }
            
            .notification-error {
                background-color: #dc3545;
            }
            
            .notification-info {
                background-color: #17a2b8;
            }
            
            .notification-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .notification-close {
                background: none;
                border: none;
                color: white;
                font-size: 18px;
                cursor: pointer;
                padding: 0;
                margin-left: 10px;
            }
            
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Ajouter au DOM
    document.body.appendChild(notification);
    
    // Gestionnaire de fermeture
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        notification.remove();
    });
    
    // Auto-suppression après 5 secondes
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Fonction pour ouvrir le modal de rendez-vous
function bookAppointment(email) {
    const appointmentModal = document.getElementById('appointmentModal');
    const memberEmailField = document.getElementById('memberEmail');
    
    if (email && appointmentModal && memberEmailField) {
        memberEmailField.value = email;
        appointmentModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    } else {
        showNotification('Impossible d\'ouvrir le formulaire de rendez-vous', 'error');
    }
}