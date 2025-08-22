<?php
namespace Altiris\FrontOffice\Controllers;

use PDO;
use PDOException;
use Exception;

class BlogController {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function index() {
        try {
            $posts = $this->getAllPosts();
            $blogDesc = $this->getBlogDescription();
            $data = [
                'posts' => $posts,
                'blogDesc' => $blogDesc,
                'meta' => [
                    'title' => 'ALTIRYS - Blog',
                    'description' => $blogDesc['description'] ?? 'Découvrez nos dernières actualités et articles'
                ]
            ];
            $this->renderView('blog', $data);
        } catch (\Exception $e) {
            error_log("[BlogController] Error: " . $e->getMessage());
            $this->renderView('blog', [
                'posts' => [],
                'blogDesc' => [],
                'meta' => [
                    'title' => 'Erreur Blog',
                    'description' => 'Erreur lors du chargement du blog.'
                ],
                'error' => 'Désolé, nous rencontrons des difficultés techniques. Veuillez réessayer plus tard.'
            ]);
        }
    }

    public function show($id) {
        try {
            $post = $this->getPostById($id);
            $blogDesc = $this->getBlogDescription();
            if (!$post) {
                header("Location: /Altiris/root/?page=blog");
                exit;
            }
            $data = [
                'post' => $post,
                'blogDesc' => $blogDesc,
                'meta' => [
                    'title' => (isset($post['title']) ? $post['title'] : 'Article') . ' | ALTIRYS',
                    'description' => $post['excerpt'] ?? ($post['content'] ?? 'Article ALTIRYS')
                ]
            ];
            $this->renderView('blog_single', $data);
        } catch (\Exception $e) {
            error_log("[BlogController] Error in show: " . $e->getMessage());
            $this->renderView('blog_single', [
                'post' => null,
                'blogDesc' => [],
                'meta' => [
                    'title' => 'Erreur Article',
                    'description' => 'Erreur lors du chargement de l\'article.'
                ],
                'error' => 'Désolé, nous rencontrons des difficultés techniques. Veuillez réessayer plus tard.'
            ]);
        }
    }

    public function getAllPosts() {
        try {
            $stmt = $this->db->prepare("SHOW TABLES LIKE 'blog_posts'");
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $stmt = $this->db->prepare("SELECT id, title, excerpt, content, image_path, created_at, category, published FROM blog_posts WHERE published = 1 ORDER BY created_at DESC");
                $stmt->execute();
                $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $stmt = $this->db->prepare("SELECT id, texte as title, texte as excerpt, texte as content, image as image_path, Date as created_at, 'Tech' as category, 1 as published FROM actualiter ORDER BY Date DESC");
                $stmt->execute();
                $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            foreach ($posts as &$post) {
                $post['image_path'] = $this->getImagePath($post['image_path']);
                if (strlen($post['title']) > 100) {
                    $post['title'] = substr($post['title'], 0, 100) . '...';
                }
                if (strlen($post['excerpt']) > 150) {
                    $post['excerpt'] = substr($post['excerpt'], 0, 150) . '...';
                }
                if (!isset($post['category'])) {
                    $post['category'] = 'Tech';
                }
            }

            return $posts;
        } catch (PDOException $e) {
            error_log("Erreur getAllPosts: " . $e->getMessage());
            return [];
        }
    }

    private function getPostById($id) {
        try {
            $stmt = $this->db->prepare("SHOW TABLES LIKE 'blog_posts'");
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $stmt = $this->db->prepare("SELECT * FROM blog_posts WHERE id = ? AND published = 1");
                $stmt->execute([$id]);
                $post = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $stmt = $this->db->prepare("SELECT id, texte as title, texte as excerpt, texte as content, image as image_path, Date as created_at, 'Tech' as category FROM actualiter WHERE id = ?");
                $stmt->execute([$id]);
                $post = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if ($post) {
                $post['image_path'] = $this->getImagePath($post['image_path']);
            }

            return $post;
        } catch (PDOException $e) {
            error_log("Erreur getPostById: " . $e->getMessage());
            return null;
        }
    }

    private function getBlogDescription() {
        try {
            $stmt = $this->db->query("SELECT titre, sous_titre, description FROM blog_desc LIMIT 1");
            $blogDesc = $stmt->fetch(PDO::FETCH_ASSOC);
            return $blogDesc ?: [];
        } catch (PDOException $e) {
            error_log("Erreur getBlogDescription: " . $e->getMessage());
            return [];
        }
    }

    private function getImagePath($imageName) {
        if (empty($imageName)) {
            return '/Altiris/Assets/Images/logo_Altirys.jpg';
        }

        $imageName = str_replace('Assets/Images/', '', $imageName);

        $paths = [
            '/Altiris/root/frontoffice/uploads/' . $imageName,
            '/Altiris/Assets/Images/' . $imageName,
            '/Altiris/assets/images/' . $imageName
        ];

        foreach ($paths as $path) {
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . $path;
            if (file_exists($fullPath)) {
                return $path;
            }
        }

        return '/Altiris/Assets/Images/logo_Altirys.jpg';
    }

    private function renderView($view, $data = []) {
        extract($data);
        require __DIR__.'/../views/partials/header.php';
        require __DIR__.'/../views/'.$view.'.php';
        require __DIR__.'/../views/partials/footer.php';
    }
}