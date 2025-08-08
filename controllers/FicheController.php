<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/Fiche.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../utils/validation.php';
require_once __DIR__ . '/../utils/csrf.php';

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
        $success_message = '';
        if (!empty($_SESSION['success_message'])) {
            $success_message = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
        }
        require __DIR__ . '/../views/fiche/list.php';
    }

    public function create()
    {
        $errors = [];
        $success_message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !checkCsrfToken($_POST['csrf_token'])) {
                $errors['general'] = 'Erreur de sécurité (CSRF). Veuillez réessayer.';
            } else {
                $libelle = isset($_POST['libelle']) ? sanitizeString($_POST['libelle']) : '';
                $description = isset($_POST['description']) ? sanitizeString($_POST['description']) : '';
                $categories = isset($_POST['categories']) && is_array($_POST['categories']) ? array_filter($_POST['categories'], 'validateInt') : [];
                // Validation
                if ($libelle === '') {
                    $errors['libelle'] = 'Le libellé est obligatoire.';
                }
                if ($description === '') {
                    $errors['description'] = 'La description est obligatoire.';
                }
                if (empty($categories)) {
                    $errors['categories'] = 'Veuillez sélectionner au moins une catégorie.';
                }
                if (empty($errors)) {
                    try {
                        $this->ficheModel->create($libelle, $description, $categories);
                        $success_message = 'Fiche créée avec succès !';
                        $_SESSION['success_message'] = $success_message;
                        header('Location: index.php?controller=fiche&action=index');
                        exit;
                    } catch (Exception $e) {
                        $errors['general'] = 'Erreur lors de la création : ' . $e->getMessage();
                    }
                }
            }
        }
        $categories = $this->categorieModel->getAll();
        require __DIR__ . '/../views/fiche/form.php';
    }

    public function update()
    {
        $id = isset($_GET['id']) && validateInt($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            header('Location: index.php?controller=fiche&action=index');
            exit;
        }
        $errors = [];
        $success_message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !checkCsrfToken($_POST['csrf_token'])) {
                $errors['general'] = 'Erreur de sécurité (CSRF). Veuillez réessayer.';
            } else {
                $libelle = isset($_POST['libelle']) ? sanitizeString($_POST['libelle']) : '';
                $description = isset($_POST['description']) ? sanitizeString($_POST['description']) : '';
                $categories = isset($_POST['categories']) && is_array($_POST['categories']) ? array_filter($_POST['categories'], 'validateInt') : [];
                // Validation
                if ($libelle === '') {
                    $errors['libelle'] = 'Le libellé est obligatoire.';
                }
                if ($description === '') {
                    $errors['description'] = 'La description est obligatoire.';
                }
                if (empty($categories)) {
                    $errors['categories'] = 'Veuillez sélectionner au moins une catégorie.';
                }
                if (empty($errors)) {
                    try {
                        $this->ficheModel->update($id, $libelle, $description, $categories);
                        $success_message = 'Fiche modifiée avec succès !';
                        $_SESSION['success_message'] = $success_message;
                        header('Location: index.php?controller=fiche&action=index');
                        exit;
                    } catch (Exception $e) {
                        $errors['general'] = 'Erreur lors de la modification : ' . $e->getMessage();
                    }
                }
            }
        } else {
            $fiche = $this->ficheModel->getById($id);
        }
        $categories = $this->categorieModel->getAll();
        $fiche_categories = $this->ficheModel->getCategories($id);
        require __DIR__ . '/../views/fiche/form.php';
    }

    public function delete()
    {
        $id = isset($_GET['id']) && validateInt($_GET['id']) ? $_GET['id'] : null;
        if ($id) {
            $this->ficheModel->delete($id);
            $_SESSION['success_message'] = 'Fiche supprimée avec succès !';
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Fiche supprimée avec succès !']);
                exit;
            }
        }
        $fiches = $this->ficheModel->getAll();
        require __DIR__ . '/../views/fiche/list.php';
    }
}
