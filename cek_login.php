<?php
session_start();

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    exit();
}
?>
