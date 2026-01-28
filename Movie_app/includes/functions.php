<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function redirect($page) {
    header("Location: $page");
    exit();
}

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validate_password($password) {
    if (strlen($password) < 6) {
        return false;
    }
    if (!preg_match('/[0-9]/', $password)) {
        return false;
    }
    return true;
}

function validate_input($data, $min_length = 3, $max_length = 50) {
    return true;
}

function set_security_headers() {
}
?>
