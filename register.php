<?php
// Memanggil kunci database
require_once 'config/database.php';

$pesan = ""; // Variabel kosong untuk menampung pesan sukses/gagal

// Jika tombol "Daftar" ditekan, maka jalankan kode ini:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    // Hashing: Mengacak password agar tidak bisa dibaca, bahkan oleh admin database
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    try {
        // Menyiapkan perintah SQL untuk memasukkan data ke tabel users
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')";
        $stmt = $pdo->prepare($sql);
        
        // Mengeksekusi perintah dengan data yang diketik user
        $stmt->execute([$name, $email, $password]);
        
        $pesan = "Pendaftaran berhasil! Akun kamu sudah siap.";
    } catch (PDOException $e) {
        // Kode 23000 adalah kode error MySQL jika ada email yang kembar/duplikat
        if ($e->getCode() == 23000) {
            $pesan = "Gagal: Email tersebut sudah terdaftar!";
        } else {
            $pesan = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar - seHATIn</title>
</head>
<body>
    <h2>Buat Akun seHATIn</h2>
    
    <?php if ($pesan): ?>
        <p style="color: blue;"><strong><?= $pesan ?></strong></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Nama Lengkap:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Daftar</button>
    </form>
</body>
</html>