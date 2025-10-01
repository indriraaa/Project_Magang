<?php
session_start();
include "koneksi.php";

$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama'] ?? '';

// Ambil data peminjaman
$result = mysqli_query($db, "SELECT p.*, b.nama_barang 
                                FROM peminjaman p 
                                JOIN barang b ON p.kd_barang = b.kd_barang 
                                ORDER BY p.kd_barang DESC");

// Total stok
$total_stok = mysqli_fetch_assoc(mysqli_query($db, "SELECT SUM(jumlah) AS total FROM barang"))['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Pengguna</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Tambahkan Bootstrap Bundle JS (wajib untuk dropdown) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Import font Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
  .menu-link .card {
    transition: 0.3s;
  }
  .menu-link:hover .card {
    background: #f8f9fa; /* warna hover */
    transform: scale(1.02);
  }
  .menu-link.active .card {
    background: #3AB0A2;   /* warna biru aktif */
    color: #fff;
  }
  .menu-link.active .card i {
    color: #fff !important; /* icon ikut putih */
  }
body { font-family: 'Poppins', sans-serif; background: #f5f7f7; margin: 0; padding: 0; }
.navbar { background-color: #3AB0A2; padding: 12px; color: white; }
/* Navbar brand khusus */
.navbar-brand { 
    font-weight: 500; 
    font-size: 14px; 
    color: #ffffff; /* ubah warna menjadi putih */
    font-family: 'Poppins', sans-serif; /* gunakan font Poppins */
}

.nav-link { font-weight: 500; font-size: 16px; color: white; }

/* Sidebar */
.sidebar-card { 
  border-radius: 12px; 
  box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
  background: #ffffff; 
  padding: 15px; 
}

.sidebar-card .card {
  border: none; 
  border-radius: 10px; 
  margin-bottom: 15px; 
  box-shadow: 0 2px 4px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
  cursor: pointer;
}
.sidebar-card .card-body { text-align: center; font-size: 0.9rem; }
.sidebar-card a { text-decoration: none; color: inherit; display: block; }

.sidebar-card .card:hover {
  background-color: #3AB0A2;
  color: #fff;
  transform: translateY(-2px);
}


.sidebar-card .card:hover h6,
.sidebar-card .card:hover p,
.sidebar-card .card:hover i { color: #fff !important; }

.sidebar-card a i { font-size: 26px; transition: transform 0.2s ease-in-out; }
.sidebar-card a:hover i { transform: scale(1.2); }

.content-card { border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); background: #ffffff; padding: 20px; }
footer { background-color: #8BC6BF; }

/* Content */
.content-box {
  background: #fff;
  padding: 25px 30px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  
}
h2 {
  text-align: center;
  margin-bottom: 25px;
  color: #000;
  font-weight: bold;
  font-size: 20px;
}
.table {
  border-radius: 8px;
  overflow: hidden;
}
.table th {
  background: #ffffff;
  color: black;
  text-align: center;      /* rata tengah horizontal */
  vertical-align: middle;  /* rata tengah vertikal */
  font-weight: 600;
}

.table td {
  text-align: center;
  vertical-align: middle;
}
.table-striped tbody tr:nth-of-type(odd) {
  background-color: #f9fdfc;
}
.btn-custom {
  display: block;
  margin: 20px auto 0;
  padding: 10px 22px;
  font-size: 16px;
  border: none;
  border-radius: 6px;
  background: #3AB0A2;
  color: white;
  cursor: pointer;
  transition: 0.3s;
  text-decoration: none;
  text-align: center;
}
.btn-custom:hover {
  background: #2f9388;
  color: #fff;
}

.action-icon { font-size: 18px; margin: 0 5px; cursor: pointer; transition: color 0.2s ease-in-out, transform 0.2s; }
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
    <a class="navbar-brand d-flex align-items-center gap-2" style="font-family: 'Poppins', sans-serif; color: white; text-decoration: none;">
      <i class="fa-solid fa-location-dot"></i>
      Direktorat Poltekkes Bandung, Jl. Padjajaran No 56 Bandung
    </a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Akun</a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<div class="container py-4">
  <div class="row g-4">

   <?php
  $current_page = basename($_SERVER['PHP_SELF']); // halaman aktif
?>
    <!-- Sidebar -->
<div class="col-md-4">
  <div class="sidebar-card">
    <div class="text-center mb-3">
      <a href="beranda.php">
        <img src="img/Logo.png" alt="Logo" style="height:50px; object-fit:contain; cursor:pointer;">
      </a>
    </div>

    <!-- BERANDA bisa diakses semua role -->
        <a href="beranda.php">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title">Beranda</h6>
              <p>Halaman Utama</p>
              <i class="fa-solid fa-house text-primary"></i>
            </div>
          </div>
        </a>

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

    <?php if($role == 'admin' || $role == 'Admin'): ?>
    <a href="riwayat.php" class="menu-link <?= ($current_page == 'riwayat.php') ? 'active' : '' ?>">
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

    <!-- Konten Riwayat -->
    <div class="col-md-8">
      <div class="content-box">
        <h2>Riwayat Peminjaman</h2>
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover align-middle">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>NIP</th>
                <th>Kontak</th>
                <th>Unit/Jurusan</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $no = 1;
              while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= $row['nama_peminjam'] ?></td>
                  <td><?= $row['nip'] ?></td>
                  <td><?= $row['kontak'] ?></td>
                  <td><?= $row['univ_jurusan'] ?></td>
                  <td><?= $row['nama_barang'] ?></td>
                  <td><?= $row['jumlah'] ?></td>
                  <td><?= $row['tanggal_pinjam'] ?></td>
                  <td><?= $row['tanggal_kembali'] ?: '-' ?></td>
                  <td>
                    <?php if($row['STATUS']=='Dipinjam'): ?>
                      <span class="badge-custom badge-pinjam">Dipinjam</span>
                    <?php else: ?>
                      <span class="badge-custom badge-kembali">Dikembalikan</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <a href="beranda.php" class="btn-custom">Kembali</a>
      </div>
    </div>

  </div>
</div>
<!-- Footer -->
<footer class="text-center py-3 mt-auto">
  <p class="mb-0">&copy; <?= date('Y'); ?> Direktorat Poltekkes Bandung</p>
</footer>

</body>
</html>
