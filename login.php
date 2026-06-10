<?php
// login.php
session_start();
require_once 'config/database.php';

$pesan = "";
$status = ""; // Untuk class alert CSS (error/success)

// --- BLOK LOGIKA (PHP) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            
            header("Location: index.php");
            exit;
        } else {
            $pesan = "Email atau password salah!";
            $status = "error";
        }
    } catch (PDOException $e) {
        $pesan = "Terjadi kesalahan sistem.";
        $status = "error";
    }
}
// --- AKHIR BLOK LOGIKA ---
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - seHATIn</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Menggunakan cache buster (?v=timestamp) agar browser selalu mengambil CSS terbaru -->
    <link rel="stylesheet" href="assets/css/base/variables.css?v=<?= time() ?>">
    <link rel="stylesheet" href="assets/css/pages/auth.css?v=<?= time() ?>">
</head>
<body>
    <div class="auth-split-layout">
        <div class="auth-visual">
            <div class="auth-visual-content">
                <h1>seHATIn</h1>
                <p>Platform evaluasi kesehatan mental dan kebiasaan sehari-hari yang membantu Anda memahami kondisi diri lebih baik, satu langkah setiap harinya.</p>
            </div>
        </div>
        
        <div class="auth-form-container">
            <div class="auth-form-wrapper">
                <div class="auth-header">
                    <h2>Selamat Datang</h2>
                    <p>Silakan masuk untuk melanjutkan evaluasi Anda.</p>
                </div>

                <?php if ($pesan): ?>
                    <div class="auth-alert <?= $status ?>">
                        <strong><?= htmlspecialchars($pesan) ?></strong>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-floating">
                        <!-- Placeholder " " (spasi) diperlukan untuk memicu label mengecil (Floating Label) -->
                        <input type="email" name="email" id="email" required placeholder=" ">
                        <label for="email">Alamat Email</label>
                    </div>

                    <div class="form-floating">
                        <input type="password" name="password" id="password" required placeholder=" ">
                        <label for="password">Password</label>
                    </div>

                    <button type="submit" class="btn-auth">Masuk ke Dashboard</button>
                </form>
                
                <div class="auth-footer">
                    Belum punya akun? <a href="register.php">Daftar sekarang</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>