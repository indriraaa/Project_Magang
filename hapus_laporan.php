<?php
include('koneksi.php');

if (isset($_POST['hapus_laporan'])) {
    // Menghapus data laporan Stok Keluar
    $sql_delete_keluar = "DELETE FROM keluar";
    $query_delete_keluar = mysqli_query($db, $sql_delete_keluar);

    // Menghapus data laporan Stok Masuk
    $sql_delete_masuk = "DELETE FROM masuk";
    $query_delete_masuk = mysqli_query($db, $sql_delete_masuk);

    // Mengembalikan ke halaman beranda
    if ($query_delete_keluar && $query_delete_masuk) {
        header("location: beranda.php?status=sukses_delete_laporan");
    } else {
        header("location: beranda.php?status=gagal_delete_laporan");
    }

    exit();
} else {
    header("location: beranda.php"); // Redirect jika aksi tidak valid
    exit();
}
?>
