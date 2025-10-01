<?php
include("koneksi.php");

// Jika form disubmit
if (isset($_POST['cetak'])) {
    $tanggal_awal = $_POST['tanggal_awal'];
    $tanggal_akhir = $_POST['tanggal_akhir'];

    // Query laporan peminjaman berdasarkan tanggal pinjam
    $sql_laporan = "SELECT p.*, b.nama_barang 
                    FROM peminjaman p
                    JOIN barang b ON p.id_barang = b.id
                    WHERE p.tanggal_pinjam BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
    $query_laporan = mysqli_query($db, $sql_laporan);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Form Laporan Peminjaman Barang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background-color: #f0fafa; }
    .card {
      border-radius: 15px;
      border: none;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .card-header {
      background-color: #6ccfcf !important;
      color: white;
      border-radius: 15px 15px 0 0 !important;
    }
    .btn-telorasin {
      background-color: #5bbcbc;
      border: none;
      color: white;
      font-weight: 500;
      transition: 0.3s;
    }
    .btn-telorasin:hover {
      background-color: #4aa7a7;
      color: #fff;
    }
    .table thead {
      background-color: #6ccfcf;
      color: white;
    }
    h5, h6 { font-weight: bold; }
    .center-form {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 90vh;
    }
  </style>
</head>
<body>

<div class="container center-form">
  <div class="col-md-8">
      
      <!-- Card Form Laporan -->
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0"><i class="fa fa-file-alt me-2"></i>Form Laporan Peminjaman Barang</h5>
        </div>
        <div class="card-body">
          <form method="POST">
            <div class="mb-3">
              <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
              <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" required>
            </div>
            <div class="mb-3">
              <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
              <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" required>
            </div>
            <button type="submit" name="cetak" class="btn btn-telorasin">
              <i class="fa fa-print"></i> Cetak Laporan
            </button>
          </form>
        </div>
      </div>

      <!-- Hasil Laporan -->
      <?php if (isset($query_laporan)) { ?>
      <div class="card mt-4">
        <div class="card-header" style="background-color:#5bbcbc !important;">
          <h6 class="mb-0">Laporan Peminjaman (<?= $tanggal_awal; ?> s/d <?= $tanggal_akhir; ?>)</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr class="text-center">
                  <th>No</th>
                  <th>Nama Barang</th>
                  <th>Nama Peminjam</th>
                  <th>Kontak</th>
                  <th>Cabang</th>
                  <th>Jumlah</th>
                  <th>Tgl Pinjam</th>
                  <th>Tgl Kembali</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                while ($lap = mysqli_fetch_assoc($query_laporan)) {
                    echo "<tr>";
                    echo "<td class='text-center'>" . $no++ . "</td>";
                    echo "<td>" . $lap['nama_barang'] . "</td>";
                    echo "<td>" . $lap['nama_peminjam'] . "</td>";
                    echo "<td>" . ($lap['kontak'] ?? '-') . "</td>";
                    echo "<td>" . ($lap['cabang'] ?? '-') . "</td>";
                    echo "<td class='text-center'>" . $lap['jumlah'] . "</td>";
                    echo "<td class='text-center'>" . $lap['tanggal_pinjam'] . "</td>";
                    echo "<td class='text-center'>" . ($lap['tanggal_kembali'] ?? '-') . "</td>";
                    echo "<td class='text-center'>" . $lap['status'] . "</td>";
                    echo "</tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <?php } ?>

  </div>
</div>

</body>
</html>
