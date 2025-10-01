<?php
include('cek_login.php');
include('koneksi.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang Masuk</title>
    <!-- Memuat CSS Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-4">
        <h2>Input Barang Masuk</h2>
        <form action="proses_tambahbarang.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="namaBarang">Nama Barang:</label>
                <input type="text" name="nama" class="form-control" id="namaBarang" placeholder="Masukkan Nama Barang">
            </div>
            <div class="form-group">
                <label for="tanggal">Tanggal:</label>
                <input type="date" name="tanggal" class="form-control" id="tanggal">
            </div>
            <div class="form-group">
                <label for="deskripsi">Foto Produk:</label>
                <input type="file" name="foto" id="foto">
            </div>
            <div class="form-group">
                <label for="stokBarang">Stok Barang:</label>
                <input type="number" name="stok" class="form-control" id="stokBarang"
                    placeholder="Masukkan Jumlah Stok Barang">
            </div>
            <div class="form-group">
                <label for="hargaBarang">Harga Barang:</label>
                <input type="number" name="harga" class="form-control" id="hargaBarang" placeholder="Masukkan Harga Barang">
            </div>
            <input type="submit" name="tambah" value="Simpan" class="btn btn-primary">
            <button class="btn btn-primary text-decoration-none"><a href="beranda.php" class="text-decoration-none"
                    style="color: white;">Kembali</a></button>
        </form>
    </div>
    <!-- Memuat JavaScript Bootstrap (jika diperlukan) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
