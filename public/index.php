<?php
// Routeur principal
$controller = $_GET['controller'] ?? 'category';
$action = $_GET['action'] ?? 'index';

// Sécurité : noms autorisés
$controllers = [
    'category' => 'CategoryController',
    'fiche' => 'FicheController',
];

if (!array_key_exists($controller, $controllers)) {
    http_response_code(404);
    exit('Contrôleur inconnu.');
}

require_once __DIR__ . '/../controllers/' . $controllers[$controller] . '.php';

$controllerClass = $controllers[$controller];
$ctrl = new $controllerClass();

if (!method_exists($ctrl, $action)) {
    http_response_code(404);
    exit('Action inconnue.');
}

$ctrl->$action();
