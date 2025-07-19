<?php
// Zentrale Authentifizierungsprüfung für alle MyBB-API-Endpunkte

global $db;

// Holt API-Key aus HTTP-Header (standardisiert für REST, aber auch alternativ falls Header anders benannt sind)
$apiKey = $_SERVER['HTTP_API_KEY'] ?? (function_exists('getallheaders') && isset(getallheaders()['Api-Key']) ? getallheaders()['Api-Key'] : null);

// Name entweder aus GET oder POST lesen
$name = $_GET['name'] ?? $_POST['name'] ?? '';

if (!$apiKey || !$name) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'Request Failed: API Key or Name is missing'
    ]);
    exit;
}

// Sonderzeichen entschärfen (kannst optional je nach DB-Setup auch parameterisiert machen)
$name = htmlspecialchars($name);
$apiKey = htmlspecialchars($apiKey);

// Zulässigen User zu API-Key suchen
$query = $db->simple_select('users', '*', "api_key = '$apiKey' AND username = '$name'");
$user = $db->fetch_array($query);

if (!$user) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'Request Failed: Invalid API Key or Name'
    ]);
    exit;
}

// Ab hier ist der Nutzer authentifiziert und $user-DB-Array steht bereit!
?>
