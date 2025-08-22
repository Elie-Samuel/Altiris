<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau message ALTIRYS</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #1183ed, #ff8412); color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; background: #f9f9f9; }
        .footer { text-align: center; padding: 10px; font-size: 12px; color: #666; }
        .message-box { background: white; padding: 15px; border-left: 4px solid #1183ed; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Nouveau message ALTIRYS</h2>
    </div>
    
    <div class="content">
        <div class="message-box">
            <p><strong>De :</strong> <?= $data['sender_name'] ?> (<?= $data['sender_email'] ?>)</p>
            <p><strong>À :</strong> <?= $data['member_name'] ?></p>
            <p><strong>Sujet :</strong> <?= $data['subject'] ?></p>
            <p><strong>Date :</strong> <?= date('d/m/Y à H:i') ?></p>
        </div>
        
        <div class="message-box">
            <h3>Message :</h3>
            <?= $data['message'] ?>
        </div>
    </div>
    
    <div class="footer">
        <p>Ce message vous a été envoyé via le système de contact ALTIRYS</p>
        <p>© <?= date('Y') ?> ALTIRYS - Tous droits réservés</p>
    </div>
</body>
</html>