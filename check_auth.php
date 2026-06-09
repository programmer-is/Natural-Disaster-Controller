<?php
session_start();
header('Content-Type: application/json');
if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'logged_in' => true,
        'user_id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'email' => $_SESSION['user_email'],
        'fullname' => $_SESSION['user_fullname'],
        'role' => $_SESSION['user_role'],
        'last_login' => $_SESSION['login_time'] ?? null
    ]);
} else {
    echo json_encode(['logged_in' => false]);
}
?>