<?php
require_once dirname(__DIR__) . '/models/Temoignage.php';

class TemoignageController {
    private $temoignage;

    public function __construct() {
        try {
            $this->temoignage = new Temoignage();
        } catch (Exception $e) {
            global $error;
            $error = "Erreur de connexion à la base de données : " . $e->getMessage();
            return;
        }
    }

    public function index($page = 1) {
        try {
            $perPage = 10;
            $temoignages = $this->temoignage->readAll($page, $perPage);
            $total = $this->temoignage->getTotalCount();
            return [
                'temoignages' => $temoignages,
                'total' => $total,
                'perPage' => $perPage,
                'currentPage' => $page
            ];
        } catch (Exception $e) {
            global $error;
            $error = "Erreur lors de la récupération des témoignages : " . $e->getMessage();
            return ['temoignages' => [], 'total' => 0, 'perPage' => 10, 'currentPage' => 1];
        }
    }

    public function create() {
        global $error;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = !empty($_POST['nom']) ? trim($_POST['nom']) : null;
            $rang = !empty($_POST['rang']) ? trim($_POST['rang']) : null;
            $text_tem = !empty($_POST['text_tem']) ? trim($_POST['text_tem']) : null;

            // Gestion de l'upload de l'image
            $image = null;
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Altiris/Assets/Images/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $maxSize = 5 * 1024 * 1024; // 5MB
                if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                    $error = "Type de fichier non autorisé. Utilisez JPG, PNG, GIF ou WebP.";
                } elseif ($_FILES['image']['size'] > $maxSize) {
                    $error = "Le fichier est trop volumineux. Taille maximale : 5MB.";
                } else {
                    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $filename = uniqid('temoignage_') . '.' . $extension;
                    $filepath = $uploadDir . $filename;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $filepath)) {
                        $image = 'Assets/Images/' . $filename;
                    } else {
                        $error = "Erreur lors de l'upload du fichier.";
                    }
                }
            }

            if (!$error) {
                try {
                    $temoignageId = $this->temoignage->create($nom, $rang, $text_tem, $image);
                    if ($temoignageId) {
                        $_SESSION['success'] = "Témoignage créé avec succès.";
                        header("Location: /Altiris/temoignages");
                        exit;
                    } else {
                        $error = "Erreur inattendue lors de la création du témoignage.";
                        if ($image && file_exists($uploadDir . basename($image))) {
                            unlink($uploadDir . basename($image));
                        }
                    }
                } catch (Exception $e) {
                    $error = $e->getMessage();
                    if ($image && file_exists($uploadDir . basename($image))) {
                        unlink($uploadDir . basename($image));
                    }
                }
            }
        }
        require_once dirname(__DIR__) . '/views/temoignages/create.php';
    }

    public function edit($id) {
        global $temoignage, $error;
        $id = (int) $id;
        if ($id <= 0) {
            $error = "ID témoignage manquant ou invalide.";
            return null;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = !empty($_POST['nom']) ? trim($_POST['nom']) : null;
            $rang = !empty($_POST['rang']) ? trim($_POST['rang']) : null;
            $text_tem = !empty($_POST['text_tem']) ? trim($_POST['text_tem']) : null;

            try {
                $current_temoignage = $this->temoignage->read($id);
                if (!$current_temoignage) {
                    $error = "Témoignage non trouvé.";
                } else {
                    $image = $current_temoignage['image'] ?? null;
                    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Altiris/Assets/Images/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                        $maxSize = 5 * 1024 * 1024; // 5MB
                        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                            $error = "Type de fichier non autorisé. Utilisez JPG, PNG, GIF ou WebP.";
                        } elseif ($_FILES['image']['size'] > $maxSize) {
                            $error = "Le fichier est trop volumineux. Taille maximale : 5MB.";
                        } else {
                            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                            $filename = uniqid('temoignage_') . '.' . $extension;
                            $filepath = $uploadDir . $filename;
                            if (move_uploaded_file($_FILES['image']['tmp_name'], $filepath)) {
                                $image = 'Assets/Images/' . $filename;
                                // Supprimer l'ancienne image
                                if (!empty($current_temoignage['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/Altiris/' . $current_temoignage['image'])) {
                                    unlink($_SERVER['DOCUMENT_ROOT'] . '/Altiris/' . $current_temoignage['image']);
                                }
                            } else {
                                $error = "Erreur lors de l'upload du fichier.";
                            }
                        }
                    }
                    if (!$error) {
                        try {
                            if ($this->temoignage->update($id, $nom, $rang, $text_tem, $image)) {
                                $_SESSION['success'] = "Témoignage mis à jour avec succès.";
                                header("Location: /Altiris/temoignages");
                                exit;
                            } else {
                                $error = "Erreur inattendue lors de la mise à jour du témoignage.";
                                if ($image && $image !== $current_temoignage['image'] && file_exists($uploadDir . basename($image))) {
                                    unlink($uploadDir . basename($image));
                                }
                            }
                        } catch (Exception $e) {
                            $error = $e->getMessage();
                            if ($image && $image !== $current_temoignage['image'] && file_exists($uploadDir . basename($image))) {
                                unlink($uploadDir . basename($image));
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                $error = "Erreur lors de la lecture du témoignage : " . $e->getMessage();
            }
        }
        try {
            $temoignage = $this->temoignage->read($id);
            if (!$temoignage) {
                $error = "Témoignage non trouvé.";
                return null;
            }
            return $temoignage;
        } catch (Exception $e) {
            $error = "Erreur lors de la lecture du témoignage : " . $e->getMessage();
            return null;
        }
    }

    public function delete($id) {
        $id = (int) $id;
        if ($id <= 0) {
            global $error;
            $error = "ID témoignage manquant ou invalide.";
        } else {
            try {
                if ($this->temoignage->delete($id)) {
                    $_SESSION['success'] = "Témoignage supprimé avec succès.";
                } else {
                    global $error;
                    $error = "Erreur inattendue lors de la suppression du témoignage.";
                }
            } catch (Exception $e) {
                global $error;
                $error = "Erreur lors de la suppression : " . $e->getMessage();
            }
        }
        header("Location: /Altiris/temoignages");
        exit;
    }

    public function deleteMultiple() {
        global $error;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids']) && is_array($_POST['ids'])) {
            $ids = array_filter(array_map('intval', $_POST['ids']));
            if (empty($ids)) {
                $error = "Aucun témoignage sélectionné.";
            } else {
                try {
                    if ($this->temoignage->deleteMultiple($ids)) {
                        $_SESSION['success'] = "Témoignages supprimés avec succès.";
                    } else {
                        $error = "Erreur inattendue lors de la suppression des témoignages.";
                    }
                } catch (Exception $e) {
                    $error = "Erreur lors de la suppression : " . $e->getMessage();
                }
            }
        } else {
            $error = "Requête invalide.";
        }
        header("Location: /Altiris/temoignages");
        exit;
    }

    public function exportCSV() {
        try {
            $temoignages = $this->temoignage->exportToCSV();
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="temoignages_' . date('Ymd_His') . '.csv"');
            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'Nom', 'Rang', 'Texte', 'Image']);
            foreach ($temoignages as $tem) {
                fputcsv($output, [
                    $tem['id_tem'],
                    $tem['Nom'],
                    $tem['rang'],
                    $tem['text_tem'],
                    $tem['image']
                ]);
            }
            fclose($output);
            exit;
        } catch (Exception $e) {
            global $error;
            $error = "Erreur lors de l'exportation : " . $e->getMessage();
            header("Location: /Altiris/temoignages");
            exit;
        }
    }

    public function search($term, $page = 1) {
        try {
            $perPage = 10;
            $temoignages = $this->temoignage->search($term, $page, $perPage);
            $total = $this->temoignage->getSearchCount($term);
            return [
                'temoignages' => $temoignages,
                'total' => $total,
                'perPage' => $perPage,
                'currentPage' => $page
            ];
        } catch (Exception $e) {
            global $error;
            $error = "Erreur lors de la recherche : " . $e->getMessage();
            return ['temoignages' => [], 'total' => 0, 'perPage' => 10, 'currentPage' => 1];
        }
    }
}
?>