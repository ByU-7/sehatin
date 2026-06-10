<?php
// register.php
require_once 'config/database.php';

$pesan = "";
$status = ""; // Untuk class alert CSS (error/success)

// --- BLOK LOGIKA (PHP) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password_raw = $_POST['password'] ?? '';
    $password = password_hash($password_raw, PASSWORD_DEFAULT); 

    try {
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $email, $password]);
        
        $pesan = "Pendaftaran berhasil! Akun Anda sudah siap.";
        $status = "success";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $pesan = "Gagal: Email tersebut sudah terdaftar!";
            $status = "error";
        } else {
            $pesan = "Terjadi kesalahan sistem.";
            $status = "error";
        }
    }
}
// --- AKHIR BLOK LOGIKA ---
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - seHATIn</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/base/variables.css?v=<?= time() ?>">
    <link rel="stylesheet" href="assets/css/pages/auth.css?v=<?= time() ?>">
</head>
<body>
    <div class="auth-split-layout">
        <div class="auth-visual">
            <div class="auth-visual-content">
                <h1>Mulai Perjalanan Anda</h1>
                <p>Daftar sekarang dan ambil langkah pertama untuk memantau kesehatan serta kebiasaan positif Anda bersama seHATIn.</p>
            </div>
        </div>
        
        <div class="auth-form-container">
            <div class="auth-form-wrapper">
                <div class="auth-header">
                    <h2>Buat Akun Baru</h2>
                    <p>Lengkapi data di bawah untuk bergabung.</p>
                </div>

                <?php if ($pesan): ?>
                    <div class="auth-alert <?= $status ?>">
                        <strong><?= htmlspecialchars($pesan) ?></strong>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-floating">
                        <input type="text" name="name" id="name" required placeholder=" ">
                        <label for="name">Nama Lengkap</label>
                    </div>

                    <div class="form-floating">
                        <input type="email" name="email" id="email" required placeholder=" ">
                        <label for="email">Alamat Email</label>
                    </div>

                    <div class="form-floating">
                        <input type="password" name="password" id="password" required placeholder=" ">
                        <label for="password">Buat Password</label>
                    </div>

                    <button type="submit" class="btn-auth">Daftar Akun Baru</button>
                </form>

                <div class="auth-footer">
                    Sudah punya akun? <a href="login.php">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>