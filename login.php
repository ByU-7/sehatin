<?php
// 1. Memulai pembagian "Gelang VIP"
session_start();

require_once 'config/database.php';

$pesan = "";

// Jika tombol "Masuk" ditekan:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // 2. Cari data user di dapur (database) berdasarkan email
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        
        // Ambil datanya (jika ada)
        $user = $stmt->fetch();

        // 3. Pengecekan: Apakah user ketemu? DAN Apakah passwordnya cocok?
        // password_verify() ini yang bisa membaca kode acak hasil hash saat register tadi
        if ($user && password_verify($password, $user['password'])) {
            
            // 4. Jika cocok, pakaikan "Gelang VIP" (Simpan data ke Session)
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role']; // Penting nanti untuk bedakan Admin/User

            // 5. Arahkan user ke halaman utama (kita buat nanti)
            header("Location: index.php");
            exit; // Hentikan script di bawahnya
        } else {
            $pesan = "Email atau password salah!";
        }
    } catch (PDOException $e) {
        $pesan = "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - seHATIn</title>
</head>
<body>
    <h2>Masuk ke seHATIn</h2>
    
    <?php if ($pesan): ?>
        <p style="color: red;"><strong><?= $pesan ?></strong></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Masuk</button>
    </form>
    
    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</body>
</html>