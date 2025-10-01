<?php
session_start();
include "koneksi.php";
require_once __DIR__ . "/phpqrcode/qrlib.php";

$role = $_SESSION['role'] ?? '';
$nama = $_SESSION['nama'] ?? '';

// --- Proses update dari modal popup ---
if (isset($_POST['update'])) {
    $kd_barang = $_POST['kd_barang'];
    $nama_barang = $_POST['nama_barang'];
    $jumlah = $_POST['jumlah'];
 
    $update = mysqli_query($db, "UPDATE barang SET nama_barang='$nama_barang', jumlah='$jumlah' WHERE kd_barang='$kd_barang'");
    if ($update) {
        echo "<script>alert('Data berhasil diperbarui'); window.location='hal.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui data');</script>";
    }
}

// Ambil stok dari database
$stok_hp = 0;
$stok_hp_masuk = 0;
$stok_hp_keluar = 0;

// Total stok barang
$query_hp = mysqli_query($db, "SELECT SUM(jumlah) AS total FROM barang");
if ($row = mysqli_fetch_assoc($query_hp)) {
    $stok_hp = $row['total'] ?? 0;
}

// Total barang masuk
$query_hp_masuk = mysqli_query($db, "SELECT COUNT(*) AS total FROM stok_masuk");
if ($row = mysqli_fetch_assoc($query_hp_masuk)) {
    $stok_hp_masuk = $row['total'] ?? 0;
}

// Total barang keluar
$query_hp_keluar = mysqli_query($db, "SELECT COUNT(*) AS total FROM stok_keluar");
if ($row = mysqli_fetch_assoc($query_hp_keluar)) {
    $stok_hp_keluar = $row['total'] ?? 0;
}

// Query stok barang dengan Telor Asin selalu di atas
$sql_barang = "SELECT * FROM barang ORDER BY (nama_barang='Telor Asin') DESC, kd_barang ASC";
$query_barang = mysqli_query($db, $sql_barang);

// Ambil semua barang untuk modal QR Code
$result_qr = mysqli_query($db, "SELECT kd_barang, nama_barang FROM barang ORDER BY kd_barang ASC");

// folder sementara untuk QR
$tempDir = __DIR__ . "/qrcodes/";
if (!file_exists($tempDir)) {
    mkdir($tempDir, 0755, true);
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

body { font-family: 'Poppins', sans-serif; background: #f5f7f7; margin: 0; padding: 0; }
.navbar { background-color: #3AB0A2; padding: 12px; color: white; }
.navbar-brand { font-weight: 500; font-size: 14px; color: #ffffff; font-family: 'Poppins', sans-serif; }
.nav-link { font-weight: 500; font-size: 16px; color: white; }
.sidebar-card { border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); background: #ffffff; padding: 15px; }
.sidebar-card .card { border: none; border-radius: 10px; margin-bottom: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.08); transition: all 0.3s ease; cursor: pointer; }
.sidebar-card .card-body { text-align: center; font-size: 0.9rem; }
.sidebar-card a { text-decoration: none; color: inherit; display: block; }
.sidebar-card .card:hover { background-color: #3AB0A2; color: #fff; transform: translateY(-2px); }
.sidebar-card .card:hover h6, .sidebar-card .card:hover p, .sidebar-card .card:hover i { color: #fff !important; }
.sidebar-card a i { font-size: 26px; transition: transform 0.2s ease-in-out; }
.sidebar-card a:hover i { transform: scale(1.2); }
.content-card { border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); background: #ffffff; padding: 20px; }
footer { background-color: #8BC6BF; }
.table thead { background: linear-gradient(90deg, #6faea7, #8BC6BF); color: #fff; text-align: center; }
.table tbody tr:hover { background-color: #f1fdfc; transition: 0.2s; }
.table td, .table th { vertical-align: middle; font-size: 14px; text-align: center; }
.table td { color: #333; }
.action-icon { font-size: 18px; margin: 0 5px; cursor: pointer; transition: color 0.2s ease-in-out, transform 0.2s; }
.icon-edit { color: #0d6efd; }
.icon-edit:hover { color: #084298; transform: scale(1.2); }
.icon-delete { color: #dc3545; }
.icon-delete:hover { color: #a71d2a; transform: scale(1.2); }

/* Style untuk modal print QR */
.qr-grid { 
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
}
.qr-item {
    text-align: center;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 8px;
}
.qr-item img {
    width: 120px;
    height: 120px;
}
.qr-item .nama {
    margin-top: 6px;
    font-size: 14px;
    font-weight: 600;
}
@media print {
    .no-print { display: none; }
    .qr-item { border: none; }
}
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

<?php $current_page = basename($_SERVER['PHP_SELF']); ?>

<div class="container py-4">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-4">
      <div class="sidebar-card">
        <div class="text-center mb-3">
            <img src="img/Logo.png" alt="Logo" style="height:50px; object-fit:contain;">
        </div>

        <!-- BERANDA -->
        <a href="beranda.php">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title">Beranda</h6>
              <p>Halaman Utama</p>
              <i class="fa-solid fa-house text-primary"></i>
            </div>
          </div>
        </a>

        <a href="hal.php" class="menu-link <?= ($current_page == 'hal.php') ? 'active' : '' ?>">
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

        <!-- Semua role -->
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

        <!-- Menu admin -->
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

    <!-- Konten -->
    <div class="col-md-8">
      <div class="content-card">
      <h5 class="mb-3 text-center" style="font-weight: bold">Daftar Stok Barang</h5>

        <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              while ($barang = mysqli_fetch_assoc($query_barang)) {
                  $highlight = ($barang['nama_barang'] == 'Telor Asin') ? 'highlight-telor' : '';
                  echo "<tr class='{$highlight}'>";
                  echo "<td class='text-center'>{$no}</td>";
                  echo "<td class='text-center'>{$barang['kd_barang']}</td>";
                  echo "<td><i class='fa-solid fa-cube me-2 text-secondary'></i> {$barang['nama_barang']}</td>";
                  echo "<td class='text-center'>{$barang['jumlah']}</td>";
                  echo "<td class='text-center'>
                          <button class='btn btn-sm text-primary' 
                                  data-bs-toggle='modal' data-bs-target='#editModal'
                                  data-kd='{$barang['kd_barang']}'
                                  data-nama='".htmlspecialchars($barang['nama_barang'], ENT_QUOTES)."'
                                  data-jumlah='{$barang['jumlah']}'>
                                  <i class='fa-regular fa-pen-to-square'></i>
                          </button>
                          <a href='javascript:void(0);' onclick='konfirmasiHapus({$barang['kd_barang']})' class='text-danger ms-2'><i class='fa-solid fa-trash'></i></a>
                          <a href='qrcode.php?kd_barang={$barang['kd_barang']}' target='_blank' class='text-success ms-2'><i class='fa-solid fa-qrcode'></i></a>
                        </td>";
                  echo "</tr>";
                  $no++;
              }
              ?>
            </tbody>
          </table>
        </div>

<!-- Tombol Kembali, Template, Import -->
<div class="mt-3 text-end">
    <a href="download_template.php" class="btn btn-warning me-2">
        <i class="fa-solid fa-download me-2"></i> Template CSV
    </a>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
        <i class="fa-solid fa-file-csv me-2"></i> Tambah Stok Barang (Import CSV)
    </button>
    <!-- Tombol untuk popup print QR -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#printQRModal">
        <i class="fa-solid fa-qrcode me-2"></i> Print Semua QR Code
    </button>
</div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Import CSV -->
<div class="modal fade" id="importModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="import_csv.php" method="post" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa-solid fa-file-csv me-2 text-success"></i>Import CSV</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Upload file CSV dengan format:<br><b>Kode Barang, Nama Barang, Jumlah</b></p>
        <input type="file" name="file_csv" accept=".csv" class="form-control" required>
      </div>
      <div class="modal-footer">
        <button type="submit" name="import" class="btn btn-primary">Import</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Barang -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Sunting Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="kd_barang" id="edit_kd_barang">
        <div class="mb-3">
          <label class="form-label">Nama Barang</label>
          <input type="text" name="nama_barang" id="edit_nama_barang" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Jumlah</label>
          <input type="number" name="jumlah" id="edit_jumlah" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Print Semua QR Code -->
<div class="modal fade" id="printQRModal" tabindex="-1" style="--bs-modal-width:90%">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header no-print">
        <h5 class="modal-title">Print Semua QR Code Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="qr-grid">
          <?php while ($row = mysqli_fetch_assoc($result_qr)): ?>
            <?php
              $fileName = $tempDir . $row['kd_barang'] . ".png";
              if (!file_exists($fileName)) {
                  QRcode::png($row['kd_barang'], $fileName, QR_ECLEVEL_L, 4, 2);
              }
            ?>
            <div class="qr-item">
              <img src="qrcodes/<?= $row['kd_barang'] ?>.png" alt="QR">
              <div class="nama"><?= htmlspecialchars($row['nama_barang']) ?></div>
              <div class="small"><?= $row['kd_barang'] ?></div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
      <div class="modal-footer no-print">
        <button onclick="window.print()" class="btn btn-primary"><i class="fa fa-print me-2"></i> Print</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<footer class="text-center py-3 mt-auto">
  <div class="container">
    <p>&copy; Direktorat Poltekkes Bandung</p>
  </div>
</footer>

<script>
function konfirmasiHapus(kd_barang) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        window.location.href = 'hapus.php?kd_barang=' + kd_barang;
    }
}

// Isi data modal edit ketika tombol edit diklik
var editModal = document.getElementById('editModal');
editModal.addEventListener('show.bs.modal', function(event){
    var button = event.relatedTarget;
    document.getElementById('edit_kd_barang').value = button.getAttribute('data-kd');
    document.getElementById('edit_nama_barang').value = button.getAttribute('data-nama');
    document.getElementById('edit_jumlah').value = button.getAttribute('data-jumlah');
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
