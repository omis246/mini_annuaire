<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../utils/validation.php';

class CategoryController
{


    public function orgchart()
    {
        $categories = $this->categorieModel->getTree();
        // Liste des racines pour le menu déroulant
        $rootCategories = array_map(function ($cat) {
            return [
                'id' => $cat['id'],
                'libelle' => $cat['libelle']
            ];
        }, $categories);
        // Générer la structure Treant.js
        function buildTreantNode($cat)
        {
            $node = [
                'id' => $cat['id'],
                'text' => ['name' => $cat['libelle']],
                // Ajout de l'id dans le text aussi pour debug
                '_debug' => 'id=' . $cat['id'],
            ];
            if (!empty($cat['enfants'])) {
                $node['children'] = [];
                foreach ($cat['enfants'] as $child) {
                    $node['children'][] = buildTreantNode($child);
                }
            }
            return $node;
        }
        $treeData = [];
        foreach ($categories as $cat) {
            $treeData[] = buildTreantNode($cat);
        }
        require __DIR__ . '/../views/category/orgchart.php';
    }
    private $categorieModel;

    public function __construct()
    {
        $this->categorieModel = new Categorie();
    }

    public function index()
    {
        $categories = $this->categorieModel->getTree();
        $success_message = '';
        if (!empty($_SESSION['success_message'])) {
            $success_message = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
        }
        require __DIR__ . '/../views/category/list.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libelle = isset($_POST['libelle']) ? sanitizeString($_POST['libelle']) : '';
            $id_parent = isset($_POST['id_parent']) && validateInt($_POST['id_parent']) ? $_POST['id_parent'] : null;
            if (empty($libelle)) {
                $erreur = 'Le libellé est obligatoire.';
            } else {
                try {
                    $this->categorieModel->create($libelle, $id_parent);
                    $_SESSION['success_message'] = 'Catégorie créée avec succès !';
                    header('Location: index.php?controller=category&action=index');
                    exit;
                } catch (Exception $e) {
                    logError('CategoryController::create - ' . $e->getMessage());
                    showUserError();
                    return;
                }
            }
        }
        $categories = $this->categorieModel->getAll();
        require __DIR__ . '/../views/category/form.php';
    }

    public function update()
    {
        $id = isset($_GET['id']) && validateInt($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            header('Location: index.php?controller=category&action=index');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libelle = isset($_POST['libelle']) ? sanitizeString($_POST['libelle']) : '';
            $id_parent = isset($_POST['id_parent']) && validateInt($_POST['id_parent']) ? $_POST['id_parent'] : null;
            if (empty($libelle)) {
                $erreur = 'Le libellé est obligatoire.';
            } else {
                try {
                    $this->categorieModel->update($id, $libelle, $id_parent);
                    $_SESSION['success_message'] = 'Catégorie modifiée avec succès !';
                    header('Location: index.php?controller=category&action=index');
                    exit;
                } catch (Exception $e) {
                    logError('CategoryController::update - ' . $e->getMessage());
                    showUserError();
                    return;
                }
            }
        }
        $categorie = $this->categorieModel->getById($id);
        $categories = $this->categorieModel->getAll();
        $parent_path = $this->categorieModel->getParentPath($id);
        require __DIR__ . '/../views/category/form.php';
    }

    public function delete()
    {
        require_once __DIR__ . '/../utils/csrf.php';
        $erreur_suppression = null;
        $success = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) && validateInt($_POST['id']) ? $_POST['id'] : null;
            $csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
            if (!checkCsrfToken($csrf_token)) {
                logError('CategoryController::delete - CSRF token invalide');
                showUserError('Erreur de sécurité. Veuillez réessayer.');
                return;
            }
        } else {
            $id = null;
        }
        if ($id) {
            try {
                // Vérifier si la catégorie a des enfants
                $enfants = $this->categorieModel->getChildren($id);
                if (!empty($enfants)) {
                    $erreur_suppression = "Impossible de supprimer : cette catégorie possède des sous-catégories.";
                } else {
                    $this->categorieModel->delete($id);
                    $success = true;
                    $_SESSION['success_message'] = 'Catégorie supprimée avec succès !';
                }
            } catch (Exception $e) {
                logError('CategoryController::delete - ' . $e->getMessage());
                showUserError();
                return;
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
