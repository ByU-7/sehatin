<?php
// history.php
require_once 'config/database.php';
require_once 'includes/auth_check.php';
requireLogin();

// Amankan Timezone agar WITA-nya akurat
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
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="assets/css/components/badges.css?v=<?= time() ?>">
    <link rel="stylesheet" href="assets/css/pages/history.css?v=<?= time() ?>">
</head>
<body>

<nav>
    <div class="logo">
        <div class="logo-circle">S</div>
        <div class="logo-text">seHATIn</div>
    </div>

    <button class="hamburger-btn" id="hamburger-btn">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div class="nav-links" id="nav-links">
        <a href="index.php" class="mobile-only">🏠 Beranda</a>
        <a href="history.php">📜 Riwayat</a>
        
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
            <a href="admin/index.php" class="mobile-only">🛠️ Dashboard Admin</a>
        <?php endif; ?>
        
        <a href="logout.php" class="logout-btn">Keluar</a>
    </div>
</nav>

<div class="wrapper">

    <main class="content">

        <div class="section-title">
            <h2>Riwayat Evaluasi Anda</h2>
        </div>

        <div class="card card-table-wrapper">
            <?php if (empty($history)): ?>
                <div class="empty-state">
                    <p>Anda belum pernah melakukan evaluasi. Silakan pilih kategori kuis di beranda untuk memulai!</p>
                    <br>
                    <a href="index.php" class="btn btn-primary">Mulai Evaluasi Sekarang</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Pengerjaan</th>
                                <th>Kategori</th>
                                <th>Skor</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($history as $row): 
                                
                                $percentage = isset($row['percentage']) ? round($row['percentage']) : 0;
                                
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
                                    <td class="date-text"><?= date('d M Y, H:i', strtotime($row['created_at'])) ?></td>
                                    <td><strong><?= htmlspecialchars($row['category_name']) ?></strong></td>
                                    <td><strong><?= $percentage ?>%</strong></td>
                                    <td>
                                        <span class="badge <?= $badge_class ?>">
                                            <?= htmlspecialchars($row['result_classification']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="result_view.php?id=<?= (int)$row['id'] ?>" class="btn btn-outline btn-sm">Detail</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </main>

    <aside class="sidebar">
        <div class="profile-box">
            <div class="avatar">
                <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
            </div>

            <h3><?= htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna') ?></h3>
            <p>Selamat datang kembali di seHATIn</p>
        </div>

        <div class="sidebar-menu">
            <a href="index.php">🏠 Beranda</a>
            <a href="history.php">📊 Riwayat Evaluasi</a>

            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <a href="admin/index.php">🛠️ Dashboard Admin</a>
            <?php endif; ?>
        </div>
    </aside>

</div> <script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const navLinks = document.getElementById('nav-links');

        if (hamburgerBtn && navLinks) {
            hamburgerBtn.addEventListener('click', function() {
                navLinks.classList.toggle('active');
            });
        }
    });
</script>

</body>
</html>