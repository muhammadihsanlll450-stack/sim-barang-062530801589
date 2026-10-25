<?php
session_start();

// 1. Kosongkan semua data sesi
$_SESSION = [];

// 2. Hapus sesi di server
session_destroy();

// 3. Kembali ke halaman login
header("Location: login.php");
exit;