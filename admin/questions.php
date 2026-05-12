<?php
// admin/questions.php
require_once '../config/database.php';
require_once '../includes/auth_check.php';
requireAdmin();

$message = "";

// 1. Ambil semua Sub-Kategori untuk Dropdown
$subcategories = $pdo->query("SELECT * FROM subcategories ORDER BY subcategory_name ASC")->fetchAll();

// 2. Proses Tambah Pertanyaan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_question'])) {
    $subcategory_id = $_POST['subcategory_id'];
    $question_text = trim($_POST['question_text']);
    $question_type = $_POST['question_type'];

    if (!empty($question_text) && !empty($subcategory_id)) {
        try {
            $sql = "INSERT INTO questions (subcategory_id, question_text, question_type) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$subcategory_id, $question_text, $question_type]);
            
            header("Location: questions.php?success=1");
            exit;
        } catch (PDOException $e) {
            $message = "Gagal menambah pertanyaan.";
        }
    }
}

// 3. Ambil data pertanyaan untuk ditampilkan (JOIN 2 Tabel)
$sql = "SELECT q.*, s.subcategory_name 
        FROM questions q 
        JOIN subcategories s ON q.subcategory_id = s.id 
        ORDER BY q.created_at DESC";
$questions = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pertanyaan - Admin</title>
</head>
<body>
    <h1>Manajemen Pertanyaan Kuis</h1>
    <a href="index.php">Kembali ke Dashboard</a>
    <hr>

    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;"><strong>Pertanyaan berhasil disimpan!</strong></p>
    <?php endif; ?>

    <form method="POST">
        <label>Pilih Sub-Kategori:</label><br>
        <select name="subcategory_id" required>
            <option value="">-- Pilih Sub-Kategori --</option>
            <?php foreach ($subcategories as $sub): ?>
                <option value="<?= $sub['id'] ?>"><?= htmlspecialchars($sub['subcategory_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Teks Pertanyaan:</label><br>
        <textarea name="question_text" rows="3" cols="50" required placeholder="Contoh: Saya merasa terbebani dengan tugas kuliah..."></textarea>
        <br><br>

        <label>Tipe Pertanyaan:</label><br>
        <select name="question_type">
            <option value="positif">Positif (Semakin setuju, skor semakin tinggi)</option>
            <option value="negatif">Negatif (Semakin setuju, skor semakin rendah)</option>
        </select>
        <br><br>

        <button type="submit" name="add_question">Simpan Pertanyaan</button>
    </form>

    <br>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Sub-Kategori</th>
                <th>Pertanyaan</th>
                <th>Tipe</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($questions as $q): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($q['subcategory_name']) ?></td>
                <td><?= htmlspecialchars($q['question_text']) ?></td>
                <td><?= ucfirst($q['question_type']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>