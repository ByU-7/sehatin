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

// 1. Fallback & Logic Persentase
$percentage = isset($result['percentage']) ? round($result['percentage']) : 0;

// 2. Logika Warna Dinamis
$color = '#dc3545'; // Merah
if ($percentage >= 80) {
    $color = '#28a745'; // Hijau
} elseif ($percentage >= 60) {
    $color = '#17a2b8'; // Biru
} elseif ($percentage >= 40) {
    $color = '#ffc107'; // Kuning
}

// 3. Logika Pesan
switch ($result['result_classification']) {
    case 'Sangat Baik': $msg = "Luar biasa! Kondisi Anda sangat prima. Pertahankan kebiasaan positif ini."; break;
    case 'Baik': $msg = "Hasil yang bagus! Anda berada di jalur yang benar, tetap konsisten ya."; break;
    case 'Cukup': $msg = "Hasil Anda cukup stabil, namun ada beberapa hal yang masih bisa ditingkatkan."; break;
    case 'Kurang': $msg = "Hasil ini menunjukkan Anda perlu memberikan perhatian lebih pada diri sendiri."; break;
    default: $msg = "Jangan berkecil hati. Hasil ini adalah alarm bagi Anda untuk mulai melakukan perubahan positif.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Evaluasi - seHATIn</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; margin: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; color: #333; }
        .container { background: #fff; padding: 40px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); max-width: 500px; width: 90%; text-align: center; }
        .category-label { color: #888; text-transform: uppercase; letter-spacing: 2px; font-size: 12px; margin-bottom: 5px; display: block; }
        .date-label { color: #bbb; font-size: 13px; margin-bottom: 20px; display: block; }
        h1 { margin: 0; color: #444; font-size: 28px; }
        
        .score-circle { width: 160px; height: 160px; border: 12px solid <?= $color ?>; border-radius: 50%; margin: 25px auto; display: flex; flex-direction: column; justify-content: center; align-items: center; transition: all 0.5s ease; }
        .score-number { font-size: 44px; font-weight: bold; color: <?= $color ?>; }
        .score-text { font-size: 14px; color: #aaa; }
        
        .classification { font-size: 26px; font-weight: bold; color: <?= $color ?>; margin-bottom: 15px; }
        .message { color: #666; line-height: 1.6; margin-bottom: 35px; padding: 0 10px; }
        
        .btn-home { background: #6c757d; color: white; text-decoration: none; padding: 14px 35px; border-radius: 30px; font-weight: bold; transition: background 0.3s, transform 0.2s; display: inline-block; }
        .btn-home:hover { background: #5a6268; transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

    <div class="container">
        <span class="category-label">Hasil Evaluasi</span>
        <h1><?= htmlspecialchars($result['category_name']) ?></h1>
        <span class="date-label">Selesai pada: <?= date('d M Y, H:i', strtotime($result['created_at'])) ?> WITA</span>

        <div class="score-circle">
            <div class="score-number"><?= $percentage ?>%</div>
            <div class="score-text">Skor Akhir</div>
        </div>

        <div class="classification">
            <?= htmlspecialchars($result['result_classification']) ?>
        </div>

        <div class="message">
            <?= htmlspecialchars($msg) ?>
        </div>

        <a href="index.php" class="btn-home">Kembali ke Beranda</a>
    </div>

</body>
</html>