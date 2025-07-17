<?php
  $status = ($responseVar === 'accepté' ? 'acceptée' : 'refusée');
  ?>
  Bonjour,

  Votre demande de rendez-vous a été <?= $status ?>.

  Détails : <?= isset($contactVar['text']) ? $contactVar['text'] : 'Aucun détail' ?>

  Cordialement,
  L'équipe Altiris