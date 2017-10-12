<?php

if (filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL) == "localhost") {
    error_reporting(-1); // -1 = on || 0 = off
} else {
    error_reporting(0); // -1 = on || 0 = off
}

// Useful php.ini file settings:
// session.cookie_lifetime = 0
// session.cookie_secure = 1
// session.cookie_httponly = 1
// session.use_only_cookies = 1
// session.entropy_file = "/dev/urandom"


if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// Function to forcibly end the session
function end_session() {
    // Use both for compatibility with all browsers
    // and all versions of PHP.
    session_unset();
    session_destroy();
}

// Does the request IP match the stored value?
function request_ip_matches_session() {
    // return false if either value is not set
    if (!isset($_SESSION['ip']) || !isset($_SERVER['REMOTE_ADDR'])) {
        return false;
    }
    if ($_SESSION['ip'] === $_SERVER['REMOTE_ADDR']) {
        return true;
    } else {
        return false;
    }
}

// Does the request user agent match the stored value?
function request_user_agent_matches_session() {
    // return false if either value is not set

    $user_agent = htmlspecialchars($_SERVER['HTTP_USER_AGENT']);
    if (!isset($_SESSION['user_agent']) || !isset($user_agent)) {
        return false;
    }
    if ($_SESSION['user_agent'] === $user_agent) {
        return true;
    } else {
        return false;
    }
}

// Has too much time passed since the last login?
function last_login_is_recent() {
    $max_elapsed = 60 * 60 * 24 * 7; // 7 days:
    // return false if value is not set
    if (!isset($_SESSION['last_login'])) {
        return false;
    }
    if (($_SESSION['last_login'] + $max_elapsed) >= time()) {
        return true;
    } else {
        return false;
    }
}

// Should the session be considered valid?
function is_session_valid() {
    $check_ip = true;
    $check_user_agent = true;
    $check_last_login = true;

    if ($check_ip && !request_ip_matches_session()) {
        return false;
    }
    if ($check_user_agent && !request_user_agent_matches_session()) {
        return false;
    }
    if ($check_last_login && !last_login_is_recent()) {
        return false;
    }
    return true;
}

// If session is not valid, end and redirect to login page.
function confirm_session_is_valid() {
    if (!is_session_valid()) {
        end_session();
        // Note that header redirection requires output buffering 
        // to be turned on or requires nothing has been output 
        // (not even whitespace).

        $host = filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        header("Location: index.php");
        exit;
    }
}

function confirm_security_level_is_valid() {
    if (!is_security_level_is_valid()) {
        end_session();
        $host = filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        header("Location: index.php");
        exit;
    }
}

// Is user logged in already?
function is_logged_in() {
    return (isset($_SESSION['logged_in']) && $_SESSION['logged_in']);
}

/* Check general security levels */

function is_security_level_is_valid() {

    if (!is_logged_in()) {
        return FALSE;
    }

    if ($_SESSION['user']->security_level === "member" || $_SESSION['user']->security_level === "sysop") {
        return TRUE;
    } else {
        return FALSE;
    }
}

// If user is not logged in, end and redirect to login page.
function confirm_user_logged_in() {
    if (!is_logged_in()) {
        end_session();
        header("Location: login.php");
        exit;
    }
}

// Actions to preform after every successful login
function after_successful_login() {
    // Regenerate session ID to invalidate the old one.
    // Super important to prevent session hijacking/fixation.
    session_regenerate_id();

    $_SESSION['logged_in'] = true;

    // Save these values in the session, even when checks aren't enabled 
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $_SESSION['last_login'] = time();

    header("Location: blog.php");
    exit;
}

// Actions to preform after every successful logout
function after_successful_logout() {
    $_SESSION['logged_in'] = false;
    end_session();
}

// Actions to preform before giving access to any 
// access-restricted page.
function protected_page() {
    confirm_user_logged_in();
    confirm_session_is_valid();
    confirm_security_level_is_valid();
}
