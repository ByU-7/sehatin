<?php
// admin/subcategories.php
require_once '../config/database.php';
require_once '../includes/auth_check.php';
requireAdmin();

$message = "";

$categories = $pdo->query("SELECT * FROM categories ORDER BY category_name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_subcategory'])) {
    $category_id = $_POST['category_id'];
    $subcategory_name = trim($_POST['subcategory_name']);

    if (!empty($subcategory_name) && !empty($category_id)) {
        try {
            $sql = "INSERT INTO subcategories (category_id, subcategory_name) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$category_id, $subcategory_name]);
            
            header("Location: subcategories.php?success=1");
            exit;
        } catch (PDOException $e) {
            $message = "Gagal menambah sub-kategori.";
        }
    }
}

$sql = "SELECT s.*, c.category_name 
        FROM subcategories s 
        JOIN categories c ON s.category_id = c.id 
        ORDER BY c.category_name ASC";
$subcategories = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Subkategori - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/pages/history.css">
    <style>
        .form-group { margin-bottom: 15px; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-family: inherit; }
        .alert-success { background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; }
        select.form-control { cursor: pointer; background-color: #f8fafc; }
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
        <div class="section-title">
            <h2>📂 Manajemen Subkategori</h2>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert-success">Sub-kategori berhasil disimpan!</div>
        <?php endif; ?>

        <div class="card" style="margin-bottom: 20px;">
            <h3>Tambah Subkategori Baru</h3>
            <p style="color: #666; margin-bottom: 15px; font-size: 0.9em;">Hubungkan rincian evaluasi ke Kategori Utama (Misal: Stres -> Akademik).</p>
            <form method="POST">
                <div class="form-group">
                    <label style="font-weight: bold; margin-bottom: 5px; display: block;">Kategori Induk:</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">-- Pilih Kategori Utama --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label style="font-weight: bold; margin-bottom: 5px; display: block;">Nama Subkategori:</label>
                    <input type="text" name="subcategory_name" class="form-control" placeholder="Misal: Kualitas Tidur" required>
                </div>
                <button type="submit" name="add_subcategory" class="btn btn-primary">Simpan Subkategori</button>
            </form>
        </div>

        <div class="card card-table-wrapper">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori Utama</th>
                            <th>Subkategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($subcategories)): ?>
                            <tr><td colspan="3" style="text-align:center;">Belum ada subkategori.</td></tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($subcategories as $sub): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><span style="color: #64748b; font-size: 0.9em;"><?= htmlspecialchars($sub['category_name']) ?></span></td>
                                <td><strong><?= htmlspecialchars($sub['subcategory_name']) ?></strong></td>
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
            <a href="categories.php">📁 Data Kategori</a>
            <a href="subcategories.php" style="background: var(--primary-soft); color: var(--primary);">📂 Data Subkategori</a>
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