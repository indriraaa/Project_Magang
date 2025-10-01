<?php
session_start();
include 'koneksi.php';

// ===== CEK LOGIN =====
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['pegawai', 'admin'])) {
    header("Location: index.php");
    exit();
} 

$role = $_SESSION['role'] ?? '';

// Query peminjaman yang masih dipinjam
$query = "
    SELECT p.kd_barang AS id_peminjaman, p.kd_barang, b.nama_barang, 
           p.nama_peminjam, p.jumlah, p.tanggal_pinjam, 
           p.tanggal_kembali, p.status
    FROM peminjaman p
    JOIN barang b ON p.kd_barang = b.kd_barang
    WHERE p.status = 'Dipinjam'
";
$peminjaman = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Pengguna</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Bootstrap Bundle JS (sudah termasuk Popper.js) -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<!-- Bootstrap JS (WAJIB untuk dropdown agar berfungsi) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
    letter-spacing: 0,5px; /* spasi antar huruf */
}


.nav-link { font-weight: reguler bold; font-size: 16px; color: white; }

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
.card-title {
  font-weight: reguler bold;
}

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

/* Form */
.container-form { 
    background: #fff; 
    padding: 35px; 
    border-radius: 14px; 
    box-shadow: 0 10px 25px rgba(0,0,0,0.08); 
    border:1px solid #d6f2ef; 
    margin-left: 18px; 
    transition: all 0.3s ease-in-out;
}
.container-form:hover {
    box-shadow: 0 12px 28px rgba(0,0,0,0.12);
}

h2 { 
    text-align: center; 
    margin-bottom: 25px; 
    color: #000000; 
    font-weight: bold; 
    font-size: 20px; 
}
label { 
    font-weight: 600; 
    color: #444; 
    margin-bottom: 6px; 
    display: block; 
    font-size: 14px;
}
.form-control, .form-select { 
    border: 1px solid #b2dfdb; 
    border-radius: 10px; 
    padding: 12px 14px; 
    margin-bottom: 20px; 
    transition: all 0.3s; 
    width: 100%; 
    font-size: 14px;
    background: #fdfdfd;
}
.form-control:focus, .form-select:focus { 
    border-color: #3AB0A2; 
    box-shadow: 0 0 8px rgba(58,176,162,0.3); 
    outline: none;
}

.action-icon { font-size: 18px; margin: 0 5px; cursor: pointer; transition: color 0.2s ease-in-out, transform 0.2s; }
.icon-edit { color: #0d6efd; }
.icon-edit:hover { color: #084298; transform: scale(1.2); }
.icon-delete { color: #dc3545; }
.icon-delete:hover { color: #a71d2a; transform: scale(1.2); }
.nav-link.dropdown-toggle {
  font-weight: reguler bold;}

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
          <img src="img/Logo.png" alt="Logo" style="height:50px; object-fit:contain;">
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
              <p>Stok: <strong><?= mysqli_fetch_assoc(mysqli_query($db,"SELECT SUM(jumlah) AS total FROM barang"))['total'] ?? 0; ?></strong></p>
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

         <a href="pengembalian.php" class="menu-link <?= ($current_page == 'pengembalian.php') ? 'active' : '' ?>">
      <div class="card">
        <div class="card-body">
          <h6 class="card-title">Pengembalian</h6>
          <p>Form Pengembalian Barang</p>
          <i class="fa-solid fa-rotate-left text-danger"></i>
        </div>
      </div>
    </a>
    
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
              <h6 class="card-title" >Laporan</h6>
              <p>Cetak / Lihat Laporan</p>
              <i class="fa-solid fa-file-alt text-info"></i>
            </div>
          </div>
        </a>
        <?php endif; ?>
      </div>
    </div>
    
    <!-- Form Pengembalian -->
    <div class="col-md-8">
      <div class="container-form">
        <h2>Pengembalian Barang</h2>
        <form action="proses_pengembalian.php" method="POST">
          <label for="id_peminjaman">Pilih Peminjaman</label>
          <select name="id_peminjaman" id="id_peminjaman" class="form-select" required>
            <option value="">-- Pilih Peminjaman --</option>
            <?php while($row = mysqli_fetch_assoc($peminjaman)): ?>
              <option value="<?= $row['id_peminjaman'] ?>">
                <?= $row['nama_peminjam'] ?> - <?= $row['nama_barang'] ?> (<?= $row['jumlah'] ?>) | Pinjam: <?= $row['tanggal_pinjam'] ?>
              </option>
            <?php endwhile; ?>
          </select>

          <label for="tanggal_kembali">Tanggal Kembali</label>
          <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control" value="<?= date('Y-m-d') ?>" required>

          <div class="d-flex justify-content-between gap-2 mt-4">
    <button type="submit" class="btn btn-success flex-fill px-3 py-2">
    Simpan
  </button>
</div>
        </form>
      </div>
    </div>

  </div>
</div>

<footer class="text-center py-3 mt-auto">
  <div class="container">
    <p>&copy; Direktorat Poltekkes Bandung</p>
  </div>
</footer>

</body>
</html>
