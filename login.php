<?php

require_once 'lib/includes/config.php'; // Configuration file for turning error reporting and connection strings to database:
require_once 'lib/functions/login_pdo_functions.inc.php';

$login = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (isset($login) && $login === 'login') {
    $data['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $data['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    //echo "<pre>" . print_r($data, 1) . "</pre>";
    $user = readLogin($data, $pdo);
    if (is_array($user)) {
        $_SESSION['user']['id'] = $user['id'];
        $_SESSION['user']['name'] = $user['name'];
        $_SESSION['user']['email'] = $user['email'];
        $_SESSION['user']['security'] = $user['security'];
        //echo "<pre>" . print_r($_SESSION, 1) . "</pre>";
        header("Location: index.php");
        exit();
    }
}