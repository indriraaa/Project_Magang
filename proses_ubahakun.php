<?php

include("koneksi.php");

session_start();

if(isset($_SESSION['email'])) {
    // Mengambil nilai email dari sesi
    $akun = $_SESSION['email'];
    // Selanjutnya, Anda dapat menggunakan variabel $akun sesuai kebutuhan
} else {
    // Jika email tidak tersedia di sesi, Anda dapat menangani kasus tersebut
    echo "Email tidak tersedia di sesi.";
}

// cek apakah tombol daftar sudah diklik atau belum
if (isset($_POST['Ubah'])) {

    // ambil data dari masing-masing field
    $email = $_POST['email'];
    $password = $_POST['password'];
    // buat query insert
    $sql = "UPDATE `pengguna` SET `email`='$email',`password`='$password' WHERE email = '$email'";
    $query = mysqli_query($db, $sql);

    // cek apakah berhasil
    if ($query) {
        // alihkan ke halaman web index dengan pesan data sukses disimpan
        header('Location: beranda.php?status=sukses_tambah');
    } else {
        // alihkan ke halaman web index dengan pesan data gagal disimpan
        header('Location: beranda.php?status=gagal_tambah');
    }
} else {
    echo "Error: " . mysqli_error($db);
}