<?php
// Vérifie si la session n'est pas démarrée et la démarre si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Inclusion des dépendances nécessaires : modèles Fiche et Category, utilitaires de validation et gestion d'erreur
require_once __DIR__ . '/../models/Fiche.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../utils/validation.php';
require_once __DIR__ . '/../utils/error.php';

class FicheController
{
    // Propriétés privées pour stocker les instances des modèles Fiche et Category
    private $ficheModel;
    private $categorieModel;

    // Constructeur : initialise les modèles Fiche et Category
    public function __construct()
    {
        $this->ficheModel = new Fiche();
        $this->categorieModel = new Categorie();
    }
    // Affiche la liste de toutes les fiches
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
    // Gère la création d'une nouvelle fiche
    public function create()
    {
        $errors = [];
        $success_message = '';

        // Si la requête est de type POST, traite le formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                    logError('FicheController::create - ' . $e->getMessage());
                    showUserError();
                    return;
                }
            }
        }
        $categories = $this->categorieModel->getAll();
        require __DIR__ . '/../views/fiche/form.php';
    }
    // Gère la modification d'une fiche existante
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
                    // Enregistre l'erreur dans les logs et affiche un message générique
                    logError('FicheController::update - ' . $e->getMessage());
                    showUserError();
                    return;
                }
            }
        } else {
            $fiche = $this->ficheModel->getById($id);
        }
        $categories = $this->categorieModel->getAll();
        $fiche_categories = $this->ficheModel->getCategories($id);
        require __DIR__ . '/../views/fiche/form.php';
    }
    // Gère la suppression d'une fiche
    public function delete()
    {
        require_once __DIR__ . '/../utils/csrf.php';

        // Inclusion de l'utilitaire CSRF pour sécuriser la suppression
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) && validateInt($_POST['id']) ? $_POST['id'] : null;
            $csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';

            // Vérifie la validité du jeton CSRF
            if (!checkCsrfToken($csrf_token)) {
                logError('FicheController::delete - CSRF token invalide');
                showUserError('Erreur de sécurité. Veuillez réessayer.');
                return;
            }
        } else {
            $id = null;
        }
        if ($id) {
            try {
                $this->ficheModel->delete($id);
                $_SESSION['success_message'] = 'Fiche supprimée avec succès !';
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Fiche supprimée avec succès !']);
                    exit;
                }
            } catch (Exception $e) {
                logError('FicheController::delete - ' . $e->getMessage());
                showUserError();
                return;
            }
        }
        $fiches = $this->ficheModel->getAll();
        require __DIR__ . '/../views/fiche/list.php';
    }
}
