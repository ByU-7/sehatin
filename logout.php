<?php
// logout.php
session_start();

// Hapus semua data di dalam session
$_SESSION = array();

// Hancurkan session-nya secara total
session_destroy();

// Tendang user kembali ke halaman login
header("Location: login.php");
exit;