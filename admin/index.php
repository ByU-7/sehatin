<?php
// admin/index.php
require_once '../config/database.php';
require_once '../includes/auth_check.php';
requireAdmin(); // Pastikan hanya admin yang bisa lihat menu ini
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - seHATIn</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components/badges.css">
</head>
<body>

<nav>
    <div class="logo">
        <div class="logo-circle" style="background: #1e293b;">A</div>
        <div class="logo-text" style="color: #1e293b;">seHATIn Admin</div>
    </div>

    <button class="hamburger-btn" id="hamburger-btn">
        <span style="background-color: #1e293b;"></span>
        <span style="background-color: #1e293b;"></span>
        <span style="background-color: #1e293b;"></span>
    </button>

    <div class="nav-links" id="nav-links">
        <a href="index.php" class="mobile-only">⚙️ Dashboard Admin</a>
        <a href="categories.php" class="mobile-only">📁 Data Kategori</a>
        <a href="subcategories.php" class="mobile-only">📂 Data Subkategori</a>
        <a href="questions.php" class="mobile-only">📝 Data Pertanyaan</a>
        
        <hr class="mobile-only" style="border:0; border-top: 1px solid #e2e8f0; margin: 5px 0; width: 100%;">
        
        <a href="../index.php" class="mobile-only">👀 Lihat Tampilan User</a>
        <a href="../logout.php" class="logout-btn">Keluar</a>
    </div>
</nav>

<div class="wrapper">

    <main class="content">

        <section class="welcome-box" style="background: linear-gradient(135deg, #1e293b, #334155);">
            <h1>Dashboard Manajemen 🛠️</h1>
            <p>
                Selamat datang, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>. 
                Anda berada di pusat kendali. Segala perubahan di sini akan langsung berdampak pada evaluasi pengguna.
            </p>
        </section>

        <div class="section-title">
            <h2>Modul Data Master</h2>
        </div>

        <div class="grid">
            <div class="card">
                <h3>Kategori</h3>
                <p>Kelola kategori utama kuis (misal: Gaya Hidup, Tingkat Stress).</p>
                <br>
                <a href="categories.php" class="btn btn-primary" style="display: block; width: 100%;">
                    Kelola Kategori &rarr;
                </a>
            </div>

            <div class="card">
                <h3>Sub-Kategori</h3>
                <p>Kelola rincian evaluasi di bawah tiap kategori (misal: Pola Tidur).</p>
                <br>
                <a href="subcategories.php" class="btn btn-primary" style="display: block; width: 100%;">
                    Kelola Subkategori &rarr;
                </a>
            </div>

            <div class="card">
                <h3>Pertanyaan</h3>
                <p>Tambah, edit, atau hapus bank soal untuk sistem evaluasi.</p>
                <br>
                <a href="questions.php" class="btn btn-primary" style="display: block; width: 100%;">
                    Kelola Pertanyaan &rarr;
                </a>
            </div>
        </div>

    </main>

    <aside class="sidebar">
        <div class="profile-box">
            <div class="avatar" style="background: #1e293b;">
                <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
            </div>
            <h3><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></h3>
            <p>Administrator Sistem</p>
            <span class="badge badge-sangat-baik" style="margin-top: 10px;">Akses Penuh</span>
        </div>

        <div class="sidebar-menu">
            <a href="index.php" style="background: var(--primary-soft); color: var(--primary);">⚙️ Dashboard Admin</a>
            <a href="categories.php">📁 Data Kategori</a>
            <a href="subcategories.php">📂 Data Subkategori</a>
            <a href="questions.php">📝 Data Pertanyaan</a>
            <hr style="border:0; border-top: 1px solid #eee; margin: 10px 0;">
            <a href="../index.php">👀 Lihat Tampilan User</a>
        </div>
    </aside>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const navLinks = document.getElementById('nav-links');

        if (hamburgerBtn && navLinks) {
            hamburgerBtn.addEventListener('click', function() {
                navLinks.classList.toggle('active');
            });
        }
    });
</script>

</body>
</html>