<?php
// history.php
require_once 'config/database.php';
require_once 'includes/auth_check.php';
requireLogin();

// Amankan Timezone agar WITA-nya akurat (Waktu Palopo/Makassar)
date_default_timezone_set('Asia/Makassar');

$user_id = $_SESSION['user_id'];

$sql = "SELECT r.*, c.category_name 
        FROM results r 
        JOIN categories c ON r.category_id = c.id 
        WHERE r.user_id = ? 
        ORDER BY r.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$history = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Evaluasi - seHATIn</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 900px; margin: 40px auto; padding: 20px; color: #333; background-color: #f4f7f6; }
        .header-box { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px; }
        h1 { margin: 0; color: #444; }
        .btn { text-decoration: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; transition: 0.2s; display: inline-block; }
        .btn-back { background: #6c757d; color: white; }
        .btn-back:hover { background: #5a6268; transform: translateY(-2px); }
        .btn-view { background: #007bff; color: white; padding: 6px 12px; font-size: 14px; border-radius: 4px; }
        .btn-view:hover { background: #0056b3; }
        
        /* Responsive Table Container */
        .card-table { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; text-align: left; min-width: 650px; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; color: #555; text-transform: uppercase; font-size: 13px; letter-spacing: 0.5px; }
        tr:hover { background-color: #fdfdfd; }
        
        /* Badge Warna Dinamis */
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; display: inline-block; }
        .badge-sangat-baik { background-color: #d4edda; color: #155724; }
        .badge-baik { background-color: #d1ecf1; color: #0c5460; }
        .badge-cukup { background-color: #fff3cd; color: #856404; }
        .badge-kurang { background-color: #f8d7da; color: #721c24; }
        .badge-unknown { background-color: #e2e3e5; color: #383d41; } /* Fallback aman */
        
        .empty-state { text-align: center; color: #888; padding: 40px 0; font-style: italic; }
    </style>
</head>
<body>

    <div class="header-box">
        <h1>Riwayat Evaluasi Anda</h1>
        <a href="index.php" class="btn btn-back">&larr; Kembali ke Beranda</a>
    </div>

    <div class="card-table">
        <?php if (empty($history)): ?>
            <div class="empty-state">
                <p>Anda belum pernah melakukan evaluasi. Silakan pilih kuis di beranda untuk memulai!</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pengerjaan</th>
                        <th>Kategori</th>
                        <th>Skor Akhir</th>
                        <th>Status Klasifikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($history as $row): 
                        
                        // 1. Fallback Aman untuk Kolom Percentage
                        $percentage = isset($row['percentage']) ? round($row['percentage']) : 0;
                        
                        // 2. Logika Badge Menggunakan Switch (Lebih Rapi & Scalable)
                        switch ($row['result_classification']) {
                            case 'Sangat Baik': $badge_class = 'badge-sangat-baik'; break;
                            case 'Baik': $badge_class = 'badge-baik'; break;
                            case 'Cukup': $badge_class = 'badge-cukup'; break;
                            case 'Kurang': $badge_class = 'badge-kurang'; break;
                            default: $badge_class = 'badge-unknown';
                        }
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d M Y, H:i', strtotime($row['created_at'])) ?> WITA</td>
                            <td><strong><?= htmlspecialchars($row['category_name']) ?></strong></td>
                            <td><span style="font-weight: bold;"><?= $percentage ?>%</span></td>
                            <td>
                                <span class="badge <?= $badge_class ?>">
                                    <?= htmlspecialchars($row['result_classification']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="result_view.php?id=<?= (int)$row['id'] ?>" class="btn btn-view">Lihat Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</body>
</html>