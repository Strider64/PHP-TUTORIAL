<?php

/* Turn on error reporting */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL) == "localhost") {
    error_reporting(-1); // -1 = on || 0 = off
} else {
    error_reporting(0); // -1 = on || 0 = off
}
$lifetime = 60 * 60 * 24 * 7;
session_start();
setcookie(session_name(), session_id(), time() + $lifetime);

date_default_timezone_set('America/Detroit');
ini_set('session.cookie_lifetime', 0);
// Prevents javascript XSS attacks aimed to steal the session ID
ini_set('session.cookie_httponly', 1);

// **PREVENTING SESSION FIXATION**
// Session ID cannot be passed through URLs
ini_set('session.use_only_cookies', 1);

// Uses a secure connection (HTTPS) if possible
ini_set('session.cookie_secure', 1);

define('EMAIL_HOST', 'smtp.1and1.com');
define('EMAIL_USERNAME', 'username');
define('EMAIL_PASSWORD', 'password');
define('EMAIL_ADDRESS', 'sample@email.com');
define('EMAIL_PORT', 587);

if (filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL) == "localhost") {
    define('DATABASE_HOST', 'localhost');
    define('DATABASE_NAME', 'myCMS');
    define('DATABASE_USERNAME', 'username * usually root *');
    define('DATABASE_PASSWORD', 'passowrd');
    define('DATABASE_TABLE', 'myBlog');
} else {
    define('DATABASE_HOST', 'remote host');
    define('DATABASE_NAME', 'remote database name');
    define('DATABASE_USERNAME', 'username');
    define('DATABASE_PASSWORD', 'password');
    define('DATABASE_TABLE', 'users');
}

/*
 * Establish a database connection.
 */
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$pdo = new PDO('mysql:host=' . DATABASE_HOST . ';dbname=' . DATABASE_NAME . ';charset=utf8', DATABASE_USERNAME, DATABASE_PASSWORD, $opt);
