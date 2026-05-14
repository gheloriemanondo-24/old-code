<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user']) && !empty($_SESSION['user']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: LogIn.php');
        exit;
    }
}

function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}

function login($username, $password) {
    // Simple hardcoded credentials (admin/admin)
    // In production, use DB with hashed passwords
    $users = [
        'admin' => ['password' => 'admin', 'role' => 'admin'],
        'staff' => ['password' => 'staff', 'role' => 'staff'],
    ];
    if (isset($users[$username]) && $users[$username]['password'] === $password) {
        $_SESSION['user'] = [
            'username' => $username,
            'role'     => $users[$username]['role'],
        ];
        return true;
    }
    return false;
}

function logout() {
    session_destroy();
    header('Location: LogIn.php');
    exit;
}
?>
