<?php
// admin/subcategories.php
require_once '../config/database.php';
require_once '../includes/auth_check.php';
requireAdmin();

$message = "";

// 1. Ambil semua Kategori Utama untuk pilihan dropdown
$categories = $pdo->query("SELECT * FROM categories ORDER BY category_name ASC")->fetchAll();

// 2. Logika Tambah Sub-Kategori (CREATE)
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

// 3. Ambil data Sub-Kategori + Nama Kategori Utamanya (READ dengan JOIN)
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
    <title>Kelola Sub-Kategori - Admin</title>
</head>
<body>
    <h1>Manajemen Sub-Kategori</h1>
    <a href="index.php">Kembali ke Dashboard</a>
    <hr>

    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;">Sub-kategori berhasil disimpan!</p>
    <?php endif; ?>

    <form method="POST">
        <label>Pilih Kategori Utama:</label><br>
        <select name="category_id" required>
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Nama Sub-Kategori:</label><br>
        <input type="text" name="subcategory_name" placeholder="Misal: Stres Akademik" required>
        <button type="submit" name="add_subcategory">Simpan</button>
    </form>

    <br>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori Utama</th>
                <th>Sub-Kategori</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($subcategories as $sub): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($sub['category_name']) ?></td>
                <td><?= htmlspecialchars($sub['subcategory_name']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>