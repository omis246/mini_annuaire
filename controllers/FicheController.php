<?php
require_once __DIR__ . '/../models/Fiche.php';
require_once __DIR__ . '/../models/Category.php';

class FicheController
{
    private $ficheModel;
    private $categorieModel;

    public function __construct()
    {
        $this->ficheModel = new Fiche();
        $this->categorieModel = new Categorie();
    }

    public function index()
    {
        $fiches = $this->ficheModel->getAll();
        require __DIR__ . '/../views/fiche/list.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libelle = $_POST['libelle'] ?? '';
            $description = $_POST['description'] ?? '';
            $categories = $_POST['categories'] ?? [];
            $this->ficheModel->create($libelle, $description, $categories);
            header('Location: index.php?controller=fiche&action=index');
            exit;
        } else {
            $categories = $this->categorieModel->getAll();
            require __DIR__ . '/../views/fiche/form.php';
        }
    }

    public function update()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?controller=fiche&action=index');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libelle = $_POST['libelle'] ?? '';
            $description = $_POST['description'] ?? '';
            $categories = $_POST['categories'] ?? [];
            $this->ficheModel->update($id, $libelle, $description, $categories);
            header('Location: index.php?controller=fiche&action=index');
            exit;
        } else {
            $fiche = $this->ficheModel->getById($id);
            $categories = $this->categorieModel->getAll();
            $fiche_categories = $this->ficheModel->getCategories($id);
            require __DIR__ . '/../views/fiche/form.php';
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->ficheModel->delete($id);
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }
        }
        header('Location: index.php?controller=fiche&action=index');
        exit;
    }
}
