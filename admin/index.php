<?php
// admin/index.php
require_once '../includes/auth_check.php';
requireAdmin(); // Pastikan hanya admin yang bisa lihat menu ini
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - seHATIn</title>
    <style>
        .card {
            border: 1px solid #ccc;
            padding: 20px;
            width: 200px;
            display: inline-block;
            margin: 10px;
            text-align: center;
            border-radius: 8px;
        }
        .card a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Dashboard Admin seHATIn</h1>
    <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>!</p>
    <hr>

    <div class="card">
        <h3>Kategori</h3>
        <p>Kelola kategori kuis</p>
        <a href="categories.php">Buka Manajemen</a>
    </div>

    <div class="card">
        <h3>Pertanyaan</h3>
        <p>Tambah/Edit soal kuis</p>
        <a href="questions.php">Buka Manajemen</a>
    </div>

    <hr>
    <a href="../index.php">Lihat Beranda User</a> | 
    <a href="../logout.php" style="color: red;">Keluar</a>
</body>
</html>