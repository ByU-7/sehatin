<?php
// Cukup panggil satpam modularnya di baris paling atas
require_once 'includes/auth_check.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda - seHATIn</title>
    <style>
        .btn-logout {
            display: inline-block;
            padding: 8px 16px;
            background-color: #ff4d4d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-family: sans-serif;
        }
        .btn-logout:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <h2>Selamat Datang di Aplikasi seHATIn!</h2>
    
    <p>Halo, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>! Senang melihatmu.</p>
    <p>Status akun kamu adalah: <strong><?= htmlspecialchars($_SESSION['user_role']) ?></strong>.</p>

    <hr>
    
    <p>Di sini nanti kita akan menampilkan daftar kuis dan hasil evaluasi.</p>

    <a href="logout.php" class="btn-logout">Keluar (Logout)</a>
</body>
</html>