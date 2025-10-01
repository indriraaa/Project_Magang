<?php
session_start();
include("koneksi.php");

// Cek apakah pengguna sudah login
if (!isset($_SESSION['login_user'])) {
    header("location: login.php");
    exit();
}

// Ambil data dari formulir
$nama = $_POST['nama'];
$email = $_POST['email'];
$password = $_POST['password'];  // Assuming the column name is 'pasword'

// Ambil nama pengguna dari sesi
$nama_pengguna = $_SESSION['login_user'];

// Update data pengguna
$sql = "UPDATE pengguna SET nama='$nama', email='$email', pasword='$password' WHERE nama='$nama_pengguna'";
$query = mysqli_query($db, $sql);

// Cek apakah query berhasil dieksekusi
if ($query) {
    // Update sesi dengan nama yang baru (jika nama diubah)
    $_SESSION['login_user'] = $nama;
    header("location: beranda.php?status=sukses");
} else {
    header("location: beranda.php?status=gagal");
}
?>
