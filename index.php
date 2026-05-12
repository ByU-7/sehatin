<?php
// 1. Selalu mulai dengan session_start() jika butuh mengecek status login
session_start();

// 2. Satpam Pengecek Gelang VIP (bjir satpam)
// Jika variabel session 'user_id' KOSONG (artinya dia belum login)
if (!isset($_SESSION['user_id'])) {
    // Tendang balik pengunjung ke halaman login (kejam hehe)
    header("Location: login.php");
    exit; // Hentikan eksekusi kode ke bawah
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda - seHATIn</title>
</head>
<body>
    <h2>Selamat Datang di Aplikasi seHATIn!</h2>
    
    <p>Halo, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>! Senang melihatmu.</p>
    
    <p>Status akun kamu adalah: <strong><?= htmlspecialchars($_SESSION['user_role']) ?></strong>.</p>

    <hr>
    
    <p> daftar kuis dan hasil evaluasi.</p>

    <a href="logout.php"><button style="color: red;">Keluar (Logout)</button></a>
</body>
</html>