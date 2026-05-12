<?php
// includes/auth_check.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /sehatin/login.php"); 
        exit;
    }
}

function requireAdmin() {
    requireLogin(); // Memastikan admin sudah login dulu
    if ($_SESSION['user_role'] !== 'admin') {
        header("Location: /sehatin/index.php");
        exit;
    }
}