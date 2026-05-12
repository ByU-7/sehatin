<?php
// config/database.php

$host = '127.0.0.1'; // atau 'localhost'
$db   = 'db_sehatin';
$user = 'root'; 
$pass = '';     
$charset = 'utf8mb4'; 

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Pengaturan tambahan PDO untuk keamanan dan pelaporan error
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Ubah error database jadi Exception (mudah dilacak)
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Ambil data dalam bentuk Array Asosiatif (lebih bersih)
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Matikan emulasi (lebih aman dari SQL Injection)
];

try {
    // Mencoba membuat koneksi baru
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Jika gagal, tangkap error-nya dan hentikan eksekusi
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}