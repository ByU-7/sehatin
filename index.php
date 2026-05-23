<?php
// index.php
require_once 'config/database.php';
require_once 'includes/auth_check.php';
requireLogin();

// Tambahkan Cache-Control agar logout sempurna
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

$categories = $pdo->query("SELECT * FROM categories ORDER BY id ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - seHATIn</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav>
    <div class="logo">
        <div class="logo-circle">S</div>
        <div class="logo-text">seHATIn</div>
    </div>

    <button class="hamburger-btn" id="hamburger-btn">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div class="nav-links" id="nav-links">
        <a href="index.php" class="mobile-only">🏠 Beranda</a>
        
        <a href="history.php">📜 Riwayat</a>
        
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
            <a href="admin/index.php" class="mobile-only">🛠️ Dashboard Admin</a>
        <?php endif; ?>
        
        <a href="logout.php" class="logout-btn">Keluar</a>
    </div>
</nav>

<div class="wrapper">



    <main class="content">

        <section class="welcome-box">
            <h1>Halo, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna') ?> 👋</h1>
            <p>
                Kadang tubuh terlihat baik-baik saja, tapi pikiran sedang berisik sendiri.
                seHATIn membantu kamu memahami kondisi diri melalui evaluasi sederhana yang lebih terarah.
            </p>
        </section>

        <div class="section-title">
            <h2>Pilih Kategori Evaluasi</h2>
        </div>

        <?php if (empty($categories)): ?>
            <div class="card">
                <p>Belum ada kategori tersedia.</p>
            </div>
        <?php else: ?>
            <div class="grid">
                <?php foreach ($categories as $cat): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($cat['category_name']) ?></h3>
                        <p>
                            Evaluasi untuk membantu memahami pola kebiasaan,
                            kondisi mental, dan pengembangan diri secara lebih terstruktur.
                        </p>
                        <a href="quiz.php?category_id=<?= $cat['id'] ?>" class="btn-quiz">
                            Mulai Evaluasi →
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
            <div class="admin-box">
                <strong>🛠️ Mode Admin Aktif</strong>
                <p style="margin-top:10px; color:#92400e;">
                    Anda memiliki akses untuk mengelola kategori, soal, dan data evaluasi pengguna.
                </p>
                <br>
                <a href="admin/index.php">Masuk ke Dashboard Admin →</a>
            </div>
        <?php endif; ?>

    </main>

        <aside class="sidebar">
        <div class="profile-box">
            <div class="avatar">
                <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
            </div>

            <h3><?= htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna') ?></h3>
            <p>Selamat datang kembali di seHATIn</p>
        </div>

        <div class="sidebar-menu">
            <a href="index.php">🏠 Beranda</a>
            <a href="history.php">📊 Riwayat Evaluasi</a>

            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <a href="admin/index.php">🛠️ Dashboard Admin</a>
            <?php endif; ?>
        </div>
    </aside>
    
</div>

</main>
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

</body>
</html>