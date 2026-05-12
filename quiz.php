<?php
// quiz.php
require_once 'config/database.php';
require_once 'includes/auth_check.php';
requireLogin();

// 1. Ambil category_id dari URL (misal: quiz.php?category_id=1)
if (!isset($_GET['category_id'])) {
    die("Kategori tidak ditemukan. Silakan pilih kategori kuis di beranda.");
}

$category_id = $_GET['category_id'];

// 2. Ambil informasi kategori untuk judul halaman
$stmt_cat = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt_cat->execute([$category_id]);
$category = $stmt_cat->fetch();

if (!$category) {
    die("Kategori tidak valid.");
}

// 3. Ambil soal yang hanya berhubungan dengan kategori ini (JOIN ke subcategories)
$sql = "SELECT q.* FROM questions q 
        JOIN subcategories s ON q.subcategory_id = s.id 
        WHERE s.category_id = ? 
        ORDER BY q.id ASC";
$stmt_q = $pdo->prepare($sql);
$stmt_q->execute([$category_id]);
$questions = $stmt_q->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kuis seHATIn - <?= htmlspecialchars($category['category_name']) ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 800px; margin: 40px auto; line-height: 1.6; color: #333; }
        .question-card { background: #fff; border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .options-group { margin-top: 15px; display: flex; flex-direction: column; gap: 10px; }
        label { cursor: pointer; padding: 8px; border-radius: 4px; transition: 0.2s; }
        label:hover { background-color: #f0f7f0; }
        input[type="radio"] { margin-right: 10px; }
        .btn-submit { background: #28a745; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; width: 100%; }
        .btn-submit:hover { background: #218838; }
    </style>
</head>
<body>

    <h1>Evaluasi: <?= htmlspecialchars($category['category_name']) ?></h1>
    <p>Silakan isi sejujur-jujurnya sesuai dengan apa yang Anda rasakan saat ini.</p>
    <hr>

    <?php if (empty($questions)): ?>
        <p>Belum ada pertanyaan untuk kategori ini. Hubungi Admin.</p>
    <?php else: ?>
        <form action="process_quiz.php" method="POST">
            <input type="hidden" name="category_id" value="<?= $category_id ?>">

            <?php $no = 1; foreach ($questions as $q): ?>
                <div class="question-card">
                    <p><strong>Pertanyaan <?= $no++ ?>:</strong></p>
                    <p><?= htmlspecialchars($q['question_text']) ?></p>
                    
                    <div class="options-group">
                        <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="1" required> Sangat Tidak Setuju</label>
                        <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="2"> Tidak Setuju</label>
                        <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="3"> Ragu-ragu / Kadang-kadang</label>
                        <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="4"> Setuju</label>
                        <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="5"> Sangat Setuju</label>
                    </div>
                </div>
            <?php endforeach; ?>

            <button type="submit" class="btn-submit">Selesai dan Lihat Hasil</button>
        </form>
    <?php endif; ?>

</body>
</html>