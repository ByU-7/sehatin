<?php
// result_view.php
require_once 'config/database.php';
require_once 'includes/auth_check.php';
requireLogin();

if (!isset($_GET['id'])) {
    die("Hasil tidak ditemukan.");
}

$result_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Query Utama
$sql = "SELECT r.*, c.category_name 
        FROM results r 
        JOIN categories c ON r.category_id = c.id 
        WHERE r.id = ? AND r.user_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$result_id, $user_id]);
$result = $stmt->fetch();

if (!$result) {
    die("Data tidak ditemukan atau Anda tidak memiliki akses.");
}

$percentage = isset($result['percentage']) ? round($result['percentage']) : 0;

// Logika Kelas CSS & Pesan (Tanpa Inline Style)
$status_class = 'status-kurang';
$msg = "Jangan berkecil hati. Hasil ini adalah alarm bagi Anda untuk mulai melakukan perubahan positif.";

switch ($result['result_classification']) {
    case 'Sangat Baik': 
        $status_class = 'status-sangat-baik';
        $msg = "Luar biasa! Kondisi Anda sangat prima. Pertahankan kebiasaan positif ini dan jadilah inspirasi bagi sekitar."; 
        break;
    case 'Baik': 
        $status_class = 'status-baik';
        $msg = "Hasil yang bagus! Anda berada di jalur yang benar, tetap konsisten dan perhatikan hal-hal kecil yang bisa ditingkatkan."; 
        break;
    case 'Cukup': 
        $status_class = 'status-cukup';
        $msg = "Kondisi Anda cukup stabil, namun ada beberapa area yang berpotensi menjadi masalah jika diabaikan terlalu lama."; 
        break;
    case 'Kurang': 
        $status_class = 'status-kurang';
        $msg = "Hasil ini menunjukkan Anda sedang dalam fase yang melelahkan. Berikan perhatian lebih dan jangan ragu untuk istirahat."; 
        break;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Evaluasi - seHATIn</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/pages/result.css">
</head>
<body>

    <nav>
        <div class="logo">
            <div class="logo-circle">S</div>
            <div class="logo-text">seHATIn</div>
        </div>
        <div class="nav-links">
            <a href="index.php">Beranda</a>
            <a href="history.php">Riwayat</a>
        </div>
    </nav>

    <main class="wrapper result-wrapper">
        
        <section class="card hero-result text-center <?= $status_class ?>">
            <span class="category-label">Laporan Evaluasi</span>
            <h1 class="result-title"><?= htmlspecialchars($result['category_name']) ?></h1>
            <span class="date-label"><?= date('d M Y, H:i', strtotime($result['created_at'])) ?> WITA</span>

            <div class="score-showcase">
                <div class="modern-score-circle">
                    <div class="inner-circle">
                        <span class="score-number"><?= $percentage ?>%</span>
                        <span class="score-text">Skor</span>
                    </div>
                </div>
            </div>

            <div class="classification-badge">
                <?= htmlspecialchars($result['result_classification']) ?>
            </div>

            <p class="message-text">
                <?= htmlspecialchars($msg) ?>
            </p>
        </section>

        <section class="card breakdown-section">
            <h3 class="breakdown-title">Analisis Subkategori</h3>
            <p class="breakdown-subtitle">Rincian performa Anda di setiap area evaluasi.</p>
            
            <div class="breakdown-list">
                <div class="breakdown-item empty-state">
                    <i>Data rincian subkategori belum tersedia untuk evaluasi ini.</i>
                </div>
            </div>
        </section>

        <div class="action-buttons">
            <a href="history.php" class="btn btn-outline">Lihat Riwayat</a>
            <a href="index.php" class="btn btn-primary">Evaluasi Kategori Lain</a>
        </div>

    </main>

</body>
</html>