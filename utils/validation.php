<?php
// Fonctions de validation et de nettoyage centralisées

/**
 * Nettoie une chaîne pour usage sûr en HTML
 */
function sanitizeString($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Valide un entier positif (ex: id)
 */
function validateInt($value) {
    return filter_var($value, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) !== false;
}

/**
 * Valide un email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Nettoie un tableau associatif (ex: POST) selon une whitelist de champs
 */
function sanitizeArray(array $input, array $fields) {
    $out = [];
    foreach ($fields as $field) {
        $out[$field] = isset($input[$field]) ? sanitizeString($input[$field]) : null;
    }
    return $out;
}
