<?php
// utils/error.php

function logError($message) {
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0775, true);
    }
    $logFile = $logDir . '/error.log';
    $date = date('Y-m-d H:i:s');
    $msg = "[$date] $message\n";
    file_put_contents($logFile, $msg, FILE_APPEND);
}

function showUserError($userMessage = 'Une erreur technique est survenue. Merci de réessayer plus tard.') {
    echo '<div style="background:#fee2e2;color:#b91c1c;padding:14px 20px;border-radius:8px;margin:20px auto;text-align:center;max-width:500px;">'
        . htmlspecialchars($userMessage) . '</div>';
}
