<?php
require_once __DIR__ . '/../models/Category.php';

class CategoryController
{
    private $categorieModel;

    public function __construct()
    {
        $this->categorieModel = new Categorie();
    }

    public function index()
    {
        $categories = $this->categorieModel->getTree();
        require __DIR__ . '/../views/category/list.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libelle = $_POST['libelle'] ?? '';
            $id_parent = $_POST['id_parent'] ?? null;
            $this->categorieModel->create($libelle, $id_parent ?: null);
            header('Location: index.php?controller=category&action=index');
            exit;
        } else {
            $categories = $this->categorieModel->getAll();
            require __DIR__ . '/../views/category/form.php';
        }
    }

    public function update()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?controller=category&action=index');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libelle = $_POST['libelle'] ?? '';
            $id_parent = $_POST['id_parent'] ?? null;
            $this->categorieModel->update($id, $libelle, $id_parent ?: null);
            header('Location: index.php?controller=category&action=index');
            exit;
        } else {
            $categorie = $this->categorieModel->getById($id);
            $categories = $this->categorieModel->getAll();
            require __DIR__ . '/../views/category/form.php';
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        $erreur_suppression = null;
        $success = false;
        if ($id) {
            // Vérifier si la catégorie a des enfants
            $enfants = $this->categorieModel->getChildren($id);
            if (!empty($enfants)) {
                $erreur_suppression = "Impossible de supprimer : cette catégorie possède des sous-catégories.";
            } else {
                $this->categorieModel->delete($id);
                $success = true;
            }
        }
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $success,
                'error' => $erreur_suppression
            ]);
            exit;
        }
        // Afficher la liste avec message d'erreur si besoin
        $categories = $this->categorieModel->getTree();
        require __DIR__ . '/../views/category/list.php';
    }
}
