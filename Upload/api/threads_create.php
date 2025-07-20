<?php

define('IN_MYBB', 1);
define('THIS_SCRIPT', 'threads_create.php');

require_once '../global.php';
require_once './api_auth.php';  // Zentrale Auth
require_once './utils/errors.php';
require_once './classes/classes.php';

header('Content-Type: application/json');

// Prüfe, ob POST-Methode verwendet wird
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Only POST requests allowed.']);
    exit;
}

// Eingaben auslesen
$data = json_decode(file_get_contents('php://input'), true);
$subject = trim($data['subject'] ?? '');
$message = trim($data['message'] ?? '');
$fid     = intval($data['fid'] ?? 0);

if (empty($subject) || empty($message) || $fid == 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing subject, message or forum ID.']);
    exit;
}

// Authentifizierter Nutzer (aus api_auth.php) ist $user
$new_thread = [
    'fid'       => $fid,
    'subject'   => $subject,
    'uid'       => $user['uid'],
    'username'  => $user['username'],
    'message'   => $message,
    'ipaddress' => $_SERVER['REMOTE_ADDR'],
    'dateline'  => TIME_NOW,
];

// MyBB-Funktion zum Thread-Erstellen
require_once MYBB_ROOT.'inc/datahandlers/post.php';
$thread_handler = new PostDataHandler('insert');
$thread_handler->set_data([
    'fid'      => $fid,
    'subject'  => $subject,
    'uid'      => $user['uid'],
    'username' => $user['username'],
    'message'  => $message,
]);

if ($thread_handler->validate_thread()) {
    $thread_handler->insert_thread();
    http_response_code(201);
    echo json_encode(['success' => true, 'thread_id' => $thread_handler->return_values['tid']]);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $thread_handler->get_friendly_errors()]);
}
