<?php
include('koneksi.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Barang</title>
  <style>
    body {
        font-family: Arial, sans-serif;
        background: #e0f2f1;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .container {
        width: 420px;
        background: #ffffff;
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-top: 8px solid #88c8c2;
    }
    h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #2c3e50;
    }
    label {
        font-weight: bold;
        color: #34495e;
    }
    input, textarea, select {
        width: 100%;
        padding: 10px;
        margin: 8px 0 16px 0;
        border: 1px solid #b2dfdb;
        border-radius: 6px;
        box-sizing: border-box;
        background: #f1fdfc;
    }
    input:focus, textarea:focus, select:focus {
        border-color: #88c8c2;
        outline: none;
        box-shadow: 0 0 5px rgba(136,200,194,0.6);
    }

    .btn-group {
        display: flex;
        justify-content: space-between;
        gap: 5px;
    }

    /* Standarkan semua tombol */
    .btn-submit, .btn-back {
        display: inline-block;
        width: 48%;
        padding: 10px;
        font-size: 16px;
        border-radius: 6px;
        cursor: pointer;
        border: none;
        box-sizing: border-box; /* penting agar padding dihitung termasuk lebar */
        text-align: center;
        line-height: normal;
        transition: 0.3s;
    }
    .btn-submit {
        background: #88c8c2;
        color: white;
    }
    .btn-submit:hover {
        background: #6fb3ad;
    }
    .btn-back {
        background: #b2dfdb;
        color: #2c3e50;
    }
    .btn-back:hover {
        background: #88c8c2;
        color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Tambah Barang</h2>
    <form action="proses_tambahbarang.php" method="post">
        <label>Nama Barang:</label>
        <input type="text" name="nama" placeholder="Masukkan Nama Barang" required>

        <label>Tanggal:</label>
        <input type="date" name="tanggal" required>

        <label>Deskripsi:</label>
        <textarea name="deskripsi" rows="3" placeholder="Masukkan Deskripsi Barang"></textarea>

        <label>Stok Barang:</label>
        <input type="number" name="stok" placeholder="Masukkan Jumlah Stok Barang" required>

        <div class="btn-group">
            <input type="submit" name="tambah" value="Tambah" class="btn-submit">
            <button type="button" class="btn-back" onclick="window.location.href='beranda.php'">Kembali</button>
        </div>
    </form>
  </div>
</body>
</html>
