<?php
// admin/categories.php
require_once '../config/database.php';
require_once '../includes/auth_check.php';
requireAdmin();

$message = "";

if (isset($_GET['success'])) {
    $message = "Kategori berhasil ditambahkan!";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $category_name = trim($_POST['category_name']);
    
    if (!empty($category_name)) {
        try {
            $sql = "INSERT INTO categories (category_name) VALUES (?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$category_name]);
            
            header("Location: categories.php?success=1");
            exit; 
        } catch (PDOException $e) {
            $message = "Gagal menambah kategori.";
        }
    } else {
        $message = "Nama kategori tidak boleh kosong!";
    }
}

$stmt = $pdo->query("SELECT * FROM categories ORDER BY created_at DESC");
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components/badges.css">
    <link rel="stylesheet" href="../assets/css/pages/history.css"> <style>
        .form-group { margin-bottom: 15px; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-family: inherit; }
        .alert-success { background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; }
    </style>
</head>
<body>

<nav>
    <div class="logo">
        <div class="logo-circle" style="background: #1e293b;">A</div>
        <div class="logo-text" style="color: #1e293b;">seHATIn Admin</div>
    </div>
    <button class="hamburger-btn" id="hamburger-btn">
        <span style="background-color: #1e293b;"></span><span style="background-color: #1e293b;"></span><span style="background-color: #1e293b;"></span>
    </button>
    <div class="nav-links" id="nav-links">
        <a href="../index.php" class="mobile-only">🏠 Beranda User</a>
        <a href="../logout.php" class="logout-btn">Keluar</a>
    </div>
</nav>

<div class="wrapper">
    <main class="content">
        <div class="section-title">
            <h2>📁 Manajemen Kategori</h2>
        </div>

        <?php if ($message): ?>
            <div class="alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="card" style="margin-bottom: 20px;">
            <h3>Tambah Kategori Baru</h3>
            <p style="color: #666; margin-bottom: 15px; font-size: 0.9em;">Buat kelompok besar kuis (Misal: Gaya Hidup, Stres).</p>
            <form method="POST" action="">
                <div class="form-group">
                    <input type="text" name="category_name" class="form-control" placeholder="Masukkan nama kategori..." required>
                </div>
                <button type="submit" name="add_category" class="btn btn-primary">Simpan Kategori</button>
            </form>
        </div>

        <div class="card card-table-wrapper">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 10%;">No</th>
                            <th style="width: 60%;">Nama Kategori</th>
                            <th style="width: 30%;">Dibuat Pada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                            <tr><td colspan="3" style="text-align:center;">Belum ada kategori.</td></tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($categories as $cat): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= htmlspecialchars($cat['category_name']) ?></strong></td>
                                <td class="date-text"><?= date('d M Y, H:i', strtotime($cat['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <aside class="sidebar">
        <div class="profile-box">
            <div class="avatar" style="background: #1e293b;">A</div>
            <h3>Admin seHATIn</h3>
        </div>
        <div class="sidebar-menu">
            <a href="index.php">⚙️ Dashboard Admin</a>
            <a href="categories.php" style="background: var(--primary-soft); color: var(--primary);">📁 Data Kategori</a>
            <a href="subcategories.php">📂 Data Subkategori</a>
            <a href="questions.php">📝 Data Pertanyaan</a>
            <hr style="border:0; border-top: 1px solid #eee; margin: 10px 0;">
            <a href="../index.php">👀 Lihat Tampilan User</a>
        </div>
    </aside>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hBtn = document.getElementById('hamburger-btn');
        const nLinks = document.getElementById('nav-links');
        if (hBtn && nLinks) { hBtn.addEventListener('click', () => nLinks.classList.toggle('active')); }
    });
</script>
</body>
</html>