<?php
// quiz.php
require_once 'config/database.php';
require_once 'includes/auth_check.php';
requireLogin();

if (!isset($_GET['category_id'])) {
    die("Kategori belum dipilih.");
}

$category_id = (int)$_GET['category_id'];

// Ambil info kategori
$stmt_cat = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt_cat->execute([$category_id]);
$category = $stmt_cat->fetch();

if (!$category) {
    die("Kategori tidak ditemukan.");
}

// Eksekusi Query untuk mengambil soal
$sql_questions = "SELECT q.* FROM questions q 
                  JOIN subcategories s ON q.subcategory_id = s.id 
                  WHERE s.category_id = ? 
                  ORDER BY q.id ASC";

$stmt_q = $pdo->prepare($sql_questions);
$stmt_q->execute([$category_id]); 
$questions = $stmt_q->fetchAll(); 
$total_questions = count($questions);
?> 
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuis: <?= htmlspecialchars($category['category_name']) ?> - seHATIn</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/pages/quiz.css">
    <link rel="stylesheet" href="assets/css/components/forms.css">
</head>
<body class="bg-subtle">

    <nav>
        <div class="logo">
            <div class="logo-circle">S</div>
            <div class="logo-text">seHATIn</div>
        </div>
        <div class="nav-links">
            <a href="index.php">Batal & Kembali</a>
        </div>
    </nav>

    <main class="quiz-page wrapper-single">
        
        <header class="card quiz-header">
            <h1 class="quiz-title"><?= htmlspecialchars($category['category_name']) ?></h1>
            <p class="quiz-desc">Jawablah dengan jujur sesuai dengan apa yang Anda rasakan akhir-akhir ini.</p>
            
            <div class="progress-container">
                <div class="progress-info">
                    <span class="progress-text">Total Pertanyaan</span>
                    <span class="progress-count"><?= $total_questions ?> Soal</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 5%;"></div>
                </div>
            </div>
        </header>

        <form action="process_quiz.php" method="POST" class="quiz-form">
            <input type="hidden" name="category_id" value="<?= $category_id ?>">

            <?php foreach ($questions as $index => $q): ?>
                <div class="card question-card">
                    <h3 class="question-text">
                        <span class="question-number"><?= $index + 1 ?>.</span> 
                        <?= htmlspecialchars($q['question_text']) ?>
                    </h3>

                    <div class="options-group">
                        <?php 
                        $options = [
                            5 => "Sangat Setuju",
                            4 => "Setuju",
                            3 => "Ragu-ragu",
                            2 => "Tidak Setuju",
                            1 => "Sangat Tidak Setuju"
                        ];
                        foreach ($options as $val => $label): 
                        ?>
                            <label class="option-card">
                                <input type="radio" name="answers[<?= $q['id'] ?>]" value="<?= $val ?>" required class="sr-only">
                                <span class="option-label"><?= $label ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="submit-section">
                <button type="submit" class="btn btn-success btn-large">Selesai & Lihat Hasil</button>
            </div>
        </form>
    </main>

</body>
</html>