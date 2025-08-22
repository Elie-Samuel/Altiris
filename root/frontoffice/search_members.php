<?php
// altiris/root/frontoffice/search_members.php

header('Content-Type: application/json; charset=utf-8');

// Initialisation de la base de données
try {
    $dbPath = realpath(__DIR__ . '/../../config/db.php');
    if (!$dbPath || !file_exists($dbPath)) {
        throw new Exception("Fichier de configuration DB introuvable");
    }
    require_once $dbPath;
    
    if (!isset($db) || !($db instanceof PDO)) {
        throw new Exception("La connexion à la base de données n'a pas pu être établie");
    }
} catch (Exception $e) {
    error_log("Erreur critique : " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur']);
    exit;
}

// Sécurisation de la requête
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
if (empty($query)) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $db->prepare("SELECT id_membre, prenom, nom, role FROM membres WHERE CONCAT(prenom, ' ', nom) LIKE :query OR role LIKE :query LIMIT 10");
    $stmt->execute(['query' => '%' . $query . '%']);
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Sécuriser les données sortantes
    foreach ($members as &$member) {
        $member['prenom'] = htmlspecialchars($member['prenom'], ENT_QUOTES, 'UTF-8');
        $member['nom'] = htmlspecialchars($member['nom'], ENT_QUOTES, 'UTF-8');
        $member['role'] = htmlspecialchars($member['role'], ENT_QUOTES, 'UTF-8');
    }

    echo json_encode($members);
} catch (PDOException $e) {
    error_log("Erreur recherche membres: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de la recherche']);
}