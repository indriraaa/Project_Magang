<?php
require_once __DIR__ . '/phpqrcode/qrlib.php';
include "koneksi.php"; // koneksi ke database

// Folder penyimpanan QR
$tempDir = __DIR__ . "/qrcodes/";
if (!file_exists($tempDir)) {
    mkdir($tempDir, 0755, true);
}

// Cek apakah ada kd_barang yang dipilih
if (!isset($_GET['kd_barang']) || empty($_GET['kd_barang'])) {
    die("Kode barang tidak ditemukan! Tambahkan ?kd_barang=XXX di URL.");
}

$kd = mysqli_real_escape_string($db, $_GET['kd_barang']);
$query = mysqli_query($db, "SELECT kd_barang, nama_barang FROM barang WHERE kd_barang='$kd'");
if (!$query) die("Query gagal: " . mysqli_error($db));
$row = mysqli_fetch_assoc($query);

if (!$row) die("Barang dengan kode $kd tidak ditemukan!");

$nama = $row['nama_barang'];
$fileName = "qr_" . $kd . ".png";
$filePath = $tempDir . $fileName;

// Generate QR Code hanya untuk barang ini
if (!file_exists($filePath)) {
    QRcode::png($kd, $filePath, QR_ECLEVEL_L, 10, 2);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>QR Code Barang <?= htmlspecialchars($kd); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #3AB0A2, #7FB7BE);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
}
.card-custom {
    background: #fff;
    border-radius: 18px;
    padding: 30px;
    max-width: 450px;
    width: 100%;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    text-align: center;
}
.qr-box {
    background: #f9f9f9;
    border: 3px dashed #3AB0A2;
    border-radius: 15px;
    padding: 15px;
    margin-bottom: 20px;
}
.qr-box img {
    width: 280px;
    height: 280px;
}
.qr-title {
    font-size: 20px;
    font-weight: 600;
    margin-top: 10px;
    color: #333;
}
.qr-subtitle {
    font-size: 16px;
    color: #555;
}
.btn-custom {
    border-radius: 10px;
    padding: 10px 18px;
    font-weight: 500;
    transition: all 0.3s ease;
}
.btn-success {
    background: #2ecc71;
    border: none;
}
.btn-success:hover {
    background: #27ae60;
    transform: translateY(-2px);
}
.btn-secondary {
    background: #636e72;
    border: none;
}
.btn-secondary:hover {
    background: #2d3436;
    transform: translateY(-2px);
}
@media print {
    .no-print { display: none; }
    body {
        background: #fff;
    }
    .card-custom {
        box-shadow: none;
        border: none;
    }
}
</style>
</head>
<body>

<div class="card-custom">
    <h4 class="mb-3"><i class="fa-solid fa-qrcode me-2"></i> QR Code Barang</h4>
    <div class="qr-box">
        <img src="qrcodes/<?= $fileName; ?>" alt="QR Code <?= htmlspecialchars($kd); ?>">
    </div>
    <div class="qr-title">Kode Barang: <?= htmlspecialchars($kd); ?></div>
    <div class="qr-subtitle"><?= htmlspecialchars($nama); ?></div>
    
    <div class="mt-4 no-print">
        <button onclick="window.print()" class="btn btn-success btn-custom me-2">
            <i class="fa fa-print"></i> Print
        </button>
        <a href="hal.php" class="btn btn-secondary btn-custom">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

</body>
</html>
