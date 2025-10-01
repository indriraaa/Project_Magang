<?php
include("koneksi.php");

// Tahun sekarang
$tahunSekarang = date("Y");

// Jika form disubmit
if (isset($_POST['cetak'])) {
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];

    // Query laporan peminjaman per bulan
    $sql_laporan = "
        SELECT p.*, b.nama_barang 
        FROM peminjaman p
        JOIN barang b ON p.kd_barang = b.kd_barang
        WHERE MONTH(p.tanggal_pinjam) = '$bulan' AND YEAR(p.tanggal_pinjam) = '$tahun'
        ORDER BY p.tanggal_pinjam ASC
    ";
    $query_laporan = mysqli_query($db, $sql_laporan);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Peminjaman Barang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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
    body { background-color: #f0fafa; font-family: 'Poppins', sans-serif; }
    .card { border-radius: 15px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .card-header { background-color: #6ccfcf !important; color: white; border-radius: 15px 15px 0 0 !important; }
    .btn-telorasin { background-color: #5bbcbc; border: none; color: white; font-weight: 500; transition: 0.3s; }
    .btn-telorasin:hover { background-color: #4aa7a7; color: #fff; }
    .table thead { background-color: #6ccfcf; color: white; }
    h5, h6 { font-weight: bold; }
    .center-form { display: flex; justify-content: center; align-items: center; min-height: 90vh; }

    /* Saat dicetak */
    @media print {
      .no-print, form, .btn, .card-header:first-of-type { display: none !important; }
      body { background: #fff; }
    }
  </style>
</head>
<body>

<div class="container center-form">
  <div class="col-md-11">

      <!-- Card Form Laporan -->
      <div class="card no-print">
        <div class="card-header">
          <h5 class="mb-0"><i class="fa fa-file-alt me-2"></i>Laporan Peminjaman Barang</h5>
        </div>
        <div class="card-body">
          <form method="POST">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="bulan" class="form-label">Bulan</label>
                <select class="form-control" id="bulan" name="bulan" required>
                  <option value="">-- Pilih Bulan --</option>
                  <?php
                  $bulan_list = [
                      1=>"Januari",2=>"Februari",3=>"Maret",4=>"April",
                      5=>"Mei",6=>"Juni",7=>"Juli",8=>"Agustus",
                      9=>"September",10=>"Oktober",11=>"November",12=>"Desember"
                  ];
                  foreach ($bulan_list as $num => $nama) {
                      $selected = (isset($bulan) && $bulan == $num) ? "selected" : "";
                      echo "<option value='$num' $selected>$nama</option>";
                  }
                  ?>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label for="tahun" class="form-label">Tahun</label>
                <input type="number" class="form-control" id="tahun" name="tahun" 
                       min="2000" max="2100" 
                       value="<?= $tahunSekarang; ?>" required>
              </div>
            </div>
            <button type="submit" name="cetak" class="btn btn-telorasin">
              <i class="fa fa-search"></i> Tampilkan Laporan
            </button>
            <div class="text-end">
  <div class="d-flex justify-content-end">
  <a href="beranda.php" class="btn btn-danger">
    <i class="fa fa-arrow-left"></i> Kembali
  </a>
</div>

</div>

          </form>
        </div>
      </div>

      <!-- Hasil Laporan -->
      <?php if (isset($query_laporan)) { ?>
      <div class="card mt-4">
        <div class="card-body">

          <!-- Judul Laporan -->
          <h5 class="text-center mb-4">
            LAPORAN PEMINJAMAN BARANG <br>
            BULAN <?= strtoupper($bulan_list[$bulan]); ?> <?= $tahun; ?>
          </h5>

          <div class="table-responsive">
            <table id="tabel-laporan" class="table table-bordered table-hover">
              <thead>
                <tr class="text-center">
                  <th>No</th>
                  <th>Nama Peminjam</th>
                  <th>Barang</th>
                  <th>Jumlah</th>
                  <th>Univ/Jurusan</th>
                  <th>Tanggal Pinjam</th>
                  <th>Tanggal Kembali</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                if (mysqli_num_rows($query_laporan) > 0) {
                  while ($lap = mysqli_fetch_assoc($query_laporan)) {
                      echo "<tr>";
                      echo "<td class='text-center'>" . $no++ . "</td>";
                      echo "<td>" . $lap['nama_peminjam'] . "</td>";
                      echo "<td>" . $lap['nama_barang'] . "</td>";
                      echo "<td class='text-center'>" . $lap['jumlah'] . "</td>";
                      echo "<td class='text-center'>" . ($lap['univ_jurusan'] ?? '-') . "</td>";
                      echo "<td class='text-center'>" . $lap['tanggal_pinjam'] . "</td>";
                      echo "<td class='text-center'>" . ($lap['tanggal_kembali'] ?? '-') . "</td>";
                      echo "<td class='text-center'>" . $lap['STATUS'] . "</td>";
                      echo "</tr>";
                  }
                } else {
                  echo "<tr><td colspan='8' class='text-center text-muted'>Tidak ada data</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>

          <!-- Tombol Cetak & Export -->
          <div class="mt-3 no-print d-flex justify-content-between">
            <div>
              <a href="laporan.php" class="btn btn-danger px-3 py-2">
                <i class="fa fa-arrow-left"></i> Batal
              </a>
            </div>
            <div>
              <button onclick="window.print()" class="btn btn-success me-2 px-3 py-2">
                <i class="fa fa-print"></i> Cetak
              </button>
              <button onclick="exportTableToExcel('tabel-laporan', 'Laporan_Peminjaman')" class="btn btn-primary px-3 py-2">
                <i class="fa fa-file-excel"></i> Export Excel
              </button>
            </div>
          </div>

        </div>
      </div>
      <?php } ?>

  </div>
</div>

<script>
function exportTableToExcel(tableID, filename = ''){
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    filename = filename?filename+'.xls':'excel_data.xls';
    
    var downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], { type: dataType });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        downloadLink.download = filename;
        downloadLink.click();
    }
}
</script>

 <!-- Footer -->
<footer class="text-center py-3 mt-auto">
  <p class="mb-0">&copy; <?= date('Y'); ?> Direktorat Poltekkes Bandung</p>
</footer>

</body>
</html>