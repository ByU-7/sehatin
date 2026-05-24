<?php
// admin/questions.php
require_once '../config/database.php';
require_once '../includes/auth_check.php';
requireAdmin();

$message = "";

$subcategories = $pdo->query("SELECT * FROM subcategories ORDER BY subcategory_name ASC")->fetchAll();

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pertanyaan - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components/badges.css">
    <link rel="stylesheet" href="../assets/css/pages/history.css">
    <style>
        .form-group { margin-bottom: 15px; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-family: inherit; }
        .alert-success { background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; }
        select.form-control { cursor: pointer; background-color: #f8fafc; }
        textarea.form-control { resize: vertical; min-height: 80px; }
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
            <h2>📝 Bank Pertanyaan</h2>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert-success">Pertanyaan berhasil disimpan ke dalam sistem!</div>
        <?php endif; ?>

        <div class="card" style="margin-bottom: 20px;">
            <h3>Tambah Pertanyaan Kuis</h3>
            <form method="POST">
                <div class="form-group">
                    <label style="font-weight: bold; margin-bottom: 5px; display: block;">Pilih Subkategori (Topik):</label>
                    <select name="subcategory_id" class="form-control" required>
                        <option value="">-- Pilih Topik Soal --</option>
                        <?php foreach ($subcategories as $sub): ?>
                            <option value="<?= $sub['id'] ?>"><?= htmlspecialchars($sub['subcategory_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label style="font-weight: bold; margin-bottom: 5px; display: block;">Teks Pertanyaan:</label>
                    <textarea name="question_text" class="form-control" placeholder="Contoh: Saya merasa terbebani dengan tugas kuliah belakangan ini..." required></textarea>
                </div>

                <div class="form-group">
                    <label style="font-weight: bold; margin-bottom: 5px; display: block;">Sifat Pertanyaan (Scoring):</label>
                    <select name="question_type" class="form-control">
                        <option value="positif">Positif (+ Sangat Setuju = Skor Tinggi)</option>
                        <option value="negatif">Negatif (- Sangat Setuju = Skor Rendah)</option>
                    </select>
                    <p style="font-size: 0.85em; color: #888; margin-top: 5px;">*Pilih negatif jika pertanyaan berupa pernyataan buruk/keluhan.</p>
                </div>

                <button type="submit" name="add_question" class="btn btn-primary">Simpan Pertanyaan</button>
            </form>
        </div>

        <div class="card card-table-wrapper">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 20%;">Topik</th>
                            <th style="width: 60%;">Pertanyaan</th>
                            <th style="width: 15%;">Tipe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($questions)): ?>
                            <tr><td colspan="4" style="text-align:center;">Belum ada pertanyaan.</td></tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($questions as $q): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><span style="color: var(--primary); font-size: 0.9em; font-weight: bold;"><?= htmlspecialchars($q['subcategory_name']) ?></span></td>
                                <td><?= htmlspecialchars($q['question_text']) ?></td>
                                <td>
                                    <?php if($q['question_type'] == 'positif'): ?>
                                        <span class="badge badge-baik">Positif</span>
                                    <?php else: ?>
                                        <span class="badge badge-kurang">Negatif</span>
                                    <?php endif; ?>
                                </td>
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
            <a href="subcategories.php">📂 Data Subkategori</a>
            <a href="questions.php" style="background: var(--primary-soft); color: var(--primary);">📝 Data Pertanyaan</a>
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