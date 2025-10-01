<?php
session_start();
include "koneksi.php";

// Data barang
$kd_barang   = "2025080901";
$nama_barang = "Laptop Lenovo Thinkpad";
$deskripsi   = "Laptop Lenovo Thinkpad dengan performa tinggi, prosesor Intel Core i5/i7, RAM 8GB-16GB, SSD 256GB-512GB, layar 14 inch. Cocok untuk kebutuhan kerja kantoran, presentasi, maupun pemrograman.";
$stok        = 5;

// Lokasi file QR Code
$folderQR = "qrcode/";
if (!file_exists($folderQR)) {
    mkdir($folderQR);
}
$fileQR = $folderQR . $kd_barang . ".png";

// Data yang dimasukkan ke QR (link ke form peminjaman)
$linkPeminjaman = "form_peminjaman.php?kd_barang=" . $kd_barang;

// Generate QR jika belum ada
if (!file_exists($fileQR)) {
    QRcode::png($linkPeminjaman, $fileQR, QR_ECLEVEL_L, 5);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Barang</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f4f4f4; }
    .container { max-width:700px; margin:20px auto; background:#fff; padding:20px; border-radius:12px; box-shadow:0 0 10px rgba(0,0,0,0.2);}
    h2 { text-align:center; margin-bottom:20px; }
    .detail { margin-bottom:15px; }
    .detail span { font-weight:bold; display:inline-block; width:150px; }
    .qr { text-align:center; margin-top:20px; }
    .qr img { border:5px solid #eee; border-radius:10px; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Detail Barang</h2>
    <div class="detail"><span>Kode Barang</span>: <?php echo $kd_barang; ?></div>
    <div class="detail"><span>Nama Barang</span>: <?php echo $nama_barang; ?></div>
    <div class="detail"><span>Deskripsi</span>: <?php echo $deskripsi; ?></div>
    <div class="detail"><span>Stok Tersedia</span>: <?php echo $stok; ?> unit</div>

    <div class="qr">
      <h3>Scan untuk Peminjaman</h3>
      <img src="<?php echo $fileQR; ?>" alt="QR Code">
      <p><i>Scan QR Code ini untuk melakukan peminjaman barang.</i></p>
    </div>
  </div>
</body>
</html>
