<?php
session_start();
include("koneksi.php");

$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama'] ?? '';

// Hanya admin yang boleh akses
if (strtolower($role) !== 'admin') {
    echo "<script>alert('Akses ditolak! Halaman ini hanya untuk admin.');window.location='beranda.php';</script>";
    exit;
}

// Ambil stok dari database
$stok_hp = 0;
$query_hp = mysqli_query($db, "SELECT SUM(jumlah) AS total FROM barang");
if ($row = mysqli_fetch_assoc($query_hp)) {
    $stok_hp = $row['total'] ?? 0;
}

// Ambil semua data pengguna
$sql_pengguna = "SELECT * FROM pengguna ORDER BY no ASC";
$result_pengguna = $db->query($sql_pengguna);

// Notifikasi status
$status_msg = "";
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case "sukses":
            $status_msg = '<div class="alert alert-success">✅ Data pengguna berhasil ditambahkan!</div>';
            break;
        case "gagal":
            $status_msg = '<div class="alert alert-danger">❌ Gagal menambahkan data pengguna!</div>';
            break;
        case "email_sudah_ada":
            $status_msg = '<div class="alert alert-warning">⚠ Email sudah digunakan, silakan pakai email lain!</div>';
            break;
        case "hapus_sukses":
            $status_msg = '<div class="alert alert-success">🗑 Data pengguna berhasil dihapus!</div>';
            break;
        case "edit_sukses":
            $status_msg = '<div class="alert alert-success">✏ Data pengguna berhasil diperbarui!</div>';
            break;
    }
}
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
    <!-- Sidebar -->
<div class="col-md-4">
  <div class="sidebar-card">
    <div class="text-center mb-3">
      <a href="beranda.php">
        <img src="img/Logo.png" alt="Logo" style="height:50px; object-fit:contain; cursor:pointer;">
      </a>
    </div>

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
          <p>Stok: <strong><?= $stok_hp; ?></strong></p>
          <i class="fa-solid fa-box text-success"></i>
        </div>
      </div>
    </a>

    <a href="pengguna.php" class="menu-link <?= ($current_page == 'pengguna.php') ? 'active' : '' ?>">
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
</div>    <!-- Konten Kanan -->
    <div class="col-md-8">
      <div class="content-card">
        <h5 class="mb-3 text-center fw-bold">Data Pengguna</h5>
        <?= $status_msg; ?>
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead>
              <tr>
                <th style="width:50px;">No</th>
                <th>Nama</th>
                <th>NIP</th>
                <th>Role</th>
                <th>Email</th>
                <th style="width:90px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if($result_pengguna->num_rows > 0): ?>
                <?php 
                $i = 1; // counter manual
                while($row = $result_pengguna->fetch_assoc()): ?>
                  <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['nip']; ?></td>
                    <td><?= $row['ROLE']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td>
                      <i class="fa-regular fa-pen-to-square action-icon icon-edit"
                         data-bs-toggle="modal" data-bs-target="#editModal"
                         data-no="<?= $row['NO']; ?>"
                         data-nama="<?= $row['nama']; ?>"
                         data-nip="<?= $row['nip']; ?>"
                         data-role="<?= $row['ROLE']; ?>"
                         data-email="<?= $row['email']; ?>"></i>
                      <i class="fa-solid fa-trash action-icon icon-delete" 
                         onclick="if(confirm('Apakah yakin ingin menghapus?')){window.location.href='proses_pengguna.php?hapus_no=<?= $row['NO']; ?>'}"></i>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-muted text-center">Belum ada data pengguna</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="proses_pengguna.php">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Pengguna</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="no" id="edit-no">
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" id="edit-nama" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">NIP</label>
            <!-- NIP tidak bisa diedit -->
            <input type="text" name="nip" id="edit-nip" class="form-control" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Role</label>
            <!-- Role tidak bisa diedit -->
            <input type="text" name="role" id="edit-role" class="form-control" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" id="edit-email" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" name="update" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<footer class="text-center py-3 mt-auto">
  <div class="container">
    <p>&copy; Direktorat Poltekkes Bandung</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto hide alert setelah 3 detik
setTimeout(function() {
    var alertBox = document.getElementById('alert-msg');
    if (alertBox) {
        alertBox.style.transition = "opacity 0.5s ease";
        alertBox.style.opacity = "0";
        setTimeout(function(){ alertBox.remove(); }, 500);
    }
}, 3000);

// Isi data ke modal saat tombol edit ditekan
var editModal = document.getElementById('editModal');
editModal.addEventListener('show.bs.modal', function (event) {
  var button = event.relatedTarget;
  document.getElementById('edit-no').value = button.getAttribute('data-no');
  document.getElementById('edit-nama').value = button.getAttribute('data-nama');
  document.getElementById('edit-nip').value = button.getAttribute('data-nip');
  document.getElementById('edit-role').value = button.getAttribute('data-role');
  document.getElementById('edit-email').value = button.getAttribute('data-email');
});
</script>
</body>
</html>

<?php $db->close(); ?>
