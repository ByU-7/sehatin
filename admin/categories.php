<?php
// admin/categories.php
require_once '../config/database.php';
require_once '../includes/auth_check.php';
requireAdmin();

$message = "";

// Cek apakah ada pesan sukses dari URL (Hasil Redirect)
if (isset($_GET['success'])) {
    $message = "Kategori berhasil ditambahkan!";
}

// --- LOGIKA TAMBAH KATEGORI (CREATE) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    // 1. Ambil dan bersihkan spasi di awal/akhir (Trim)
    $category_name = trim($_POST['category_name']);
    
    // 2. Validasi: Jangan biarkan input kosong atau hanya spasi
    if (!empty($category_name)) {
        try {
            $sql = "INSERT INTO categories (category_name) VALUES (?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$category_name]);
            
            // 3. POLA PRG: Redirect setelah berhasil simpan
            header("Location: categories.php?success=1");
            exit; 

        } catch (PDOException $e) {
            $message = "Gagal menambah kategori. Silakan coba lagi.";
            // Untuk belajar, kamu bisa lihat log error di file log server, bukan di layar user.
        }
    } else {
        $message = "Nama kategori tidak boleh kosong!";
    }
}

// --- LOGIKA AMBIL DATA (READ) ---
$stmt = $pdo->query("SELECT * FROM categories ORDER BY created_at DESC");
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kategori - Admin</title>
</head>
<body>
    <h1>Manajemen Kategori Kuis</h1>
    <a href="../index.php">Kembali ke Beranda</a>
    <hr>

    <?php if ($message): ?>
        <p style="color: green;"><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <h3>Tambah Kategori Baru</h3>
    <form method="POST" action="">
        <input type="text" name="category_name" placeholder="Misal: Tingkat Stres" required>
        <button type="submit" name="add_category">Simpan</button>
    </form>

    <br>

    <h3>Daftar Kategori</h3>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Dibuat Pada</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($categories)): ?>
                <tr><td colspan="3">Belum ada kategori.</td></tr>
            <?php else: ?>
                <?php $no = 1; foreach ($categories as $cat): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($cat['category_name']) ?></td>
                    <td><?= $cat['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>