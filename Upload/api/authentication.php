<?php

define('IN_MYBB', 1);
define('THIS_SCRIPT', 'authentication.php'); // Korrigiere den Script-Namen

require_once '../global.php';
require_once './api_auth.php';     // Zentrale Authentifizierung
require_once './utils/errors.php';
require_once './classes/classes.php';

header('Content-Type: application/json');

// Nur hier ankommen, wenn API-Key und Name korrekt waren ($user ist jetzt gesetzt)
// Optional: Standardavatar setzen
global $mybb;
if (!$user['avatar'] || empty($user['avatar'])) {
    $user['avatar'] = $mybb->settings['useravatar'];
}

$userObj = new User($user);
http_response_code(200);
echo $userObj->toJson();
