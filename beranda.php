<?php
session_start();
include "koneksi.php";

$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama'] ?? '';

// Total stok barang
$total_stok = 0;
$q_stok = mysqli_query($db, "SELECT SUM(jumlah) AS total FROM barang");
if ($row = mysqli_fetch_assoc($q_stok)) {
    $total_stok = $row['total'] ?? 0;
}

// Total barang dipinjam
$total_pinjam = 0;
$q_pinjam = mysqli_query($db, "SELECT SUM(jumlah) AS total FROM peminjaman WHERE status='Dipinjam'");
if ($row = mysqli_fetch_assoc($q_pinjam)) {
    $total_pinjam = $row['total'] ?? 0;
}

// Riwayat peminjaman (5 terakhir)
$q_riwayat = mysqli_query(
    $db,
    "SELECT p.*, b.nama_barang 
     FROM peminjaman p 
     JOIN barang b ON p.kd_barang = b.kd_barang 
     ORDER BY p.NO DESC LIMIT 5"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Pengguna</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  .menu-link .card {
    transition: 0.3s;
  }
  .menu-link:hover .card {
    background: #f8f9fa;
    transform: scale(1.02);
  }
  .menu-link.active .card {
    background: #3AB0A2;
    color: #fff;
  }
  .menu-link.active .card i {
    color: #fff !important;
  }
body { font-family: 'Poppins', sans-serif; background: #f5f7f7; }
.navbar { background-color: #3AB0A2; padding: 12px; color: white; }
.navbar-brand { font-weight: 500; font-size: 14px; color: #fff; }
.nav-link { font-weight: 500; font-size: 16px; color: white; }

.sidebar-card { border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); background: #fff; padding: 15px; }
.sidebar-card .card { border: none; border-radius: 10px; margin-bottom: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.08); transition: 0.3s; cursor: pointer; }
.sidebar-card .card-body { text-align: center; font-size: 0.9rem; }
.sidebar-card a { text-decoration: none; color: inherit; display: block; }
.sidebar-card .card:hover { background-color: #3AB0A2; color: #fff; transform: translateY(-2px); }
.sidebar-card .card:hover h6, .sidebar-card .card:hover p, .sidebar-card .card:hover i { color: #fff !important; }
.sidebar-card a i { font-size: 26px; transition: 0.2s; }
.sidebar-card a:hover i { transform: scale(1.2); }

.content-card { border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); background: #fff; padding: 20px; }
footer { background-color: #8BC6BF; }

.table thead { background: linear-gradient(90deg, #6faea7, #8BC6BF); color: #fff; text-align: center; }
.table tbody tr:hover { background-color: #f1fdfc; }
.table td, .table th { vertical-align: middle; font-size: 14px; text-align: center; }
.action-icon { font-size: 18px; margin: 0 5px; cursor: pointer; transition: 0.2s; }
.icon-edit { color: #0d6efd; }
.icon-edit:hover { color: #084298; transform: scale(1.2); }
.icon-delete { color: #dc3545; }
.icon-delete:hover { color: #a71d2a; transform: scale(1.2); }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" style="color: white; text-decoration: none;">
      <i class="fa-solid fa-location-dot"></i>
      Direktorat Poltekkes Bandung, Jl. Padjajaran No 56 Bandung
    </a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Akun</a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<div class="container py-4">
  <div class="row g-4">

   <?php $current_page = basename($_SERVER['PHP_SELF']); ?>

    <!-- Sidebar kiri -->
    <div class="col-md-4 d-flex">
      <div class="sidebar-card w-100">
        <div class="text-center mb-3">
          <img src="img/Logo.png" alt="Logo" style="height:50px; object-fit:contain;">
        </div>

        <a href="beranda.php" class="menu-link <?= ($current_page == 'beranda.php') ? 'active' : '' ?>">
  <div class="card">
    <div class="card-body">
      <h6 class="card-title">Beranda</h6>
      <p>Halaman Utama</p>
      <i class="fa-solid fa-house"></i>
    </div>
  </div>
</a>


        <!-- Menu khusus admin -->
        <?php if($role == 'admin' || $role == 'Admin'): ?>
        <a href="hal.php">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title">Data Barang</h6>
              <p>Stok: <strong><?= $total_stok; ?></strong></p>
              <i class="fa-solid fa-box text-success"></i>
            </div>
          </div>
        </a>
        <a href="pengguna.php">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title">Pegawai</h6>
              <p>Data Pegawai</p>
              <i class="fa-solid fa-user text-primary"></i>
            </div>
          </div>
        </a>
        <?php endif; ?>

        <!-- Bisa diakses semua role -->
        <a href="peminjaman.php">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title">Peminjaman</h6>
              <p>Form Peminjaman Barang</p>
              <i class="fa-solid fa-hand-holding text-success"></i>
            </div>
          </div>
        </a>
        <a href="pengembalian.php">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title">Pengembalian</h6>
              <p>Form Pengembalian Barang</p>
              <i class="fa-solid fa-rotate-left text-danger"></i>
            </div>
          </div>
        </a>

        <!-- Menu khusus admin -->
        <?php if($role == 'admin' || $role == 'Admin'): ?>
        <a href="riwayat.php">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title">Riwayat</h6>
              <p>Lihat Riwayat</p>
              <i class="fa-solid fa-clock-rotate-left text-warning"></i>
            </div>
          </div>
        </a>
        <a href="laporan.php">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title">Laporan</h6>
              <p>Cetak / Lihat Laporan</p>
              <i class="fa-solid fa-file-alt text-info"></i>
            </div>
          </div>
        </a>
        <?php endif; ?>
      </div>
    </div>

<!-- Konten kanan -->
<div class="col-md-8 d-flex">
  <div class="w-100">

<!-- Ringkasan Data Barang -->
<div class="content-card mb-4">
  <h5 class="mb-3 text-center fw-bold">Ringkasan Data Barang</h5>
  <div class="row text-center g-3">
    <?php if($role == 'admin' || $role == 'Admin'): ?>
      <!-- Admin: tampilkan total -->
      <div class="col-md-4">
        <div class="stat-card shadow-sm p-3 rounded" 
             style="background-color:#e8f0ff; color:#0d6efd;">
          <h6>Total Stok</h6>
          <h3 class="fw-bold"><?= $total_stok; ?></h3>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card shadow-sm p-3 rounded" 
             style="background-color:#ffeaea; color:#dc3545;">
          <h6>Dipinjam</h6>
          <h3 class="fw-bold"><?= $total_pinjam; ?></h3>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card shadow-sm p-3 rounded" 
             style="background-color:#e9f9f1; color:#198754;">
          <h6>Tersedia</h6>
          <h3 class="fw-bold"><?= $total_stok - $total_pinjam; ?></h3>
        </div>
      </div>
    <?php else: ?>
      <!-- Pegawai: tampilkan barang yang dipinjam oleh dirinya -->
      <?php
      $total_pinjam_pegawai = 0;
      $q_pinjam_pegawai = mysqli_query($db, "
        SELECT SUM(jumlah) AS total 
        FROM peminjaman 
        WHERE nama_peminjam = '$nama' AND STATUS='Dipinjam'
      ");
      if($row = mysqli_fetch_assoc($q_pinjam_pegawai)){
        $total_pinjam_pegawai = $row['total'] ?? 0;
      }
      ?>
      <div class="col-md-12">
        <div class="stat-card shadow-sm p-3 rounded" 
             style="background-color:#ffeaea; color:#dc3545;">
          <h6>Barang yang Sedang Anda Pinjam</h6>
          <h3 class="fw-bold"><?= $total_pinjam_pegawai; ?></h3>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>


<!-- Riwayat Peminjaman -->
<div class="content-card">
  <h5 class="mb-3 text-center fw-bold">Riwayat Peminjaman Barang</h5>
  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead>
        <tr>
          <th>No</th>
          <?php if($role == 'admin' || $role == 'Admin'): ?>
            <th>Nama Peminjam</th>
          <?php endif; ?>
          <th>Barang</th>
          <th>Jumlah</th>
          <th>Tanggal Pinjam</th>
          <th>Tanggal Kembali</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $no = 1;

        if($role == 'admin' || $role == 'Admin'){
          // Admin lihat semua
          $q_pinjam = mysqli_query($db, "
            SELECT p.*, b.nama_barang 
            FROM peminjaman p
            JOIN barang b ON p.kd_barang = b.kd_barang
            ORDER BY p.NO DESC LIMIT 10
          ");
        } else {
          // Pegawai hanya lihat miliknya
          $q_pinjam = mysqli_query($db, "
            SELECT p.*, b.nama_barang 
            FROM peminjaman p
            JOIN barang b ON p.kd_barang = b.kd_barang
            WHERE p.nama_peminjam = '$nama'
            ORDER BY p.NO DESC LIMIT 10
          ");
        }

        while ($r = mysqli_fetch_assoc($q_pinjam)) {
          echo "<tr>
            <td>{$no}</td>";
          
          if($role == 'admin' || $role == 'Admin'){
            echo "<td>{$r['nama_peminjam']}</td>";
          }

          echo "
            <td><i class='fa-solid fa-box text-secondary me-1'></i> {$r['nama_barang']}</td>
            <td>{$r['jumlah']}</td>
            <td>{$r['tanggal_pinjam']}</td>
            <td>".($r['tanggal_kembali'] ? $r['tanggal_kembali'] : "-")."</td>
            <td><span class='badge ".($r['STATUS']=='Dipinjam'?'bg-danger':'bg-success')."'>{$r['STATUS']}</span></td>
          </tr>";
          $no++;
        }
        ?>
      </tbody>
    </table>
  </div>
</div>


  </div>
</div>
</div>
</div>

<!-- Footer -->
<footer class="text-center py-3 mt-auto">
  <p class="mb-0">&copy; <?= date('Y'); ?> Direktorat Poltekkes Bandung</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>