<?php
require_once 'lib/includes/config.php'; // Configuration file for turning error reporting and connection strings to database:
require_once 'lib/functions/login_pdo_functions.inc.php';
require_once 'lib/functions/validate_functions.inc.php';
require_once 'lib/functions/verification_functions.inc.php';
require_once 'lib/vendor/autoload.php';

$submit = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (isset($submit) && $submit === 'register') {
    $data['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $data['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $data['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $data['security'] = 'public';
    $data['confirmation'] = bin2hex(random_bytes(16));
    $fields = is_field_empty($data);
    if (!is_array($fields)) {
        $error['empty'] = "All fields must be fields fillout, please re-enter!";
    }
    $fields = is_name_unique($data, $pdo);
    if (!is_array($fields)) {
        $error['name'] = "Username is unavailable, please re-enter!";
    } else {
        $result = createLogin($fields, $pdo);
        $status = email_confirmation($fields);
        if ($result && $status) {
            header("Location: index.php");
            exit();
        }
    }
    
    
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, width=device-width" />
        <title>PHP, PDO &amp; MySQL Tutorial</title>
        <!--
        I decided to make an external stylesheet to keep the code down. The stylesheet stays in the same folder
        as the other files. Feel free to use this file or create your own CSS.
        -->
        <link rel="stylesheet" href="lib/css/reset.css">
        <link rel="stylesheet" href="lib/css/style.css">
    </head>
    <body>
        <?php require_once 'lib/includes/heading.inc.php'; ?>
        <div class="container bg-color form-bg">
            <form id="register" action="" method="post">
                <h1>Registration Form</h1>
                <input type="hidden" name="action" value="register">
                <label for="name">Username</label>
                <input id="name" type="text" name="name" value="">
                <label for="password">Password</label>
                <input id="password" type="password" name="password">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="">
                <input id="submitBTN" type="submit" name="submit" value="Register">
            </form>
        </div>
    </body>
</html>
