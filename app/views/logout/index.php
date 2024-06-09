<?php
// Mulai sesi jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hancurkan semua data sesi
session_destroy();

// Mengarahkan pengguna ke halaman login
header('Location: ' . BASEURL . '/login');
exit;
?>
