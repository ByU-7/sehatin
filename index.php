<?php
// index.php
require_once 'config/database.php';
require_once 'includes/auth_check.php';
requireLogin();

// Ambil semua kategori kuis
$categories = $pdo->query("SELECT * FROM categories ORDER BY id ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda - seHATIn</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 900px; margin: 40px auto; padding: 20px; color: #333; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px; }
        .card { border: 1px solid #eee; padding: 25px; border-radius: 12px; text-align: center; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: 0.3s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .btn-quiz { display: inline-block; margin-top: 15px; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .btn-logout { background: #ff4757; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; display: inline-block; margin-top: 40px; }
        .admin-link { margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #6c757d; }
    </style>
</head>
<body>

    <header>
        <h1>Selamat Datang di Aplikasi seHATIn!</h1>
        <p>Halo, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna') ?></strong>! Senang melihatmu kembali.</p>
    </header>

    <main>
        <h3>Pilih Kategori Evaluasi:</h3>

        <?php if (empty($categories)): ?>
            <p style="color: #666; font-style: italic;">Belum ada kategori kuis yang tersedia saat ini.</p>
        <?php else: ?>
            <div class="grid">
                <?php foreach ($categories as $cat): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($cat['category_name']) ?></h3>
                        <p style="font-size: 0.9em; color: #666;">Mulai evaluasi untuk mengetahui kondisi kesehatan mental Anda di bidang ini.</p>
                        <a href="quiz.php?category_id=<?= $cat['id'] ?>" class="btn-quiz">Mulai Kuis</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <hr style="margin-top: 40px; border: 0; border-top: 1px solid #eee;">

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <div class="admin-link">
            <strong>Mode Admin Terdeteksi:</strong> 
            <a href="admin/index.php" style="color: #007bff; text-decoration: none; font-weight: bold;">Buka Dashboard Manajemen &raquo;</a>
        </div>
    <?php endif; ?>

    <a href="logout.php" class="btn-logout">Keluar (Logout)</a>

</body>
</html>
<!-- hehehe -->