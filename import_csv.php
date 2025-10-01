<?php
include("koneksi.php");

// Proses jika form upload CSV dikirim
if (isset($_POST['import'])) {
    $fileName = $_FILES['file_csv']['tmp_name'];

    if ($_FILES['file_csv']['size'] > 0) {
        $file = fopen($fileName, "r");
        $row = 0;

        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
            $row++;

            // Lewati baris pertama (header CSV)
            if ($row == 1) continue;

            // Ambil data sesuai urutan kolom di CSV
            // $kd_barang   = mysqli_real_escape_string($db, $data[0]);
            $nama_barang = mysqli_real_escape_string($db, $data[0]);
            $jumlah      = mysqli_real_escape_string($db, $data[1]);

            // Simpan ke tabel barang
            $query = "INSERT INTO barang (nama_barang, jumlah) 
                      VALUES ('$nama_barang','$jumlah')";
            mysqli_query($db, $query);
        }

        fclose($file);
        echo "<script>alert('Import data barang berhasil!'); window.location='hal.php';</script>";
    } else {
        echo "<script>alert('File CSV tidak boleh kosong!'); window.location='hal.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Import CSV Barang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container">
  <div class="card shadow">
    <div class="card-header bg-info text-white">
      <h5 class="mb-0"><i class="fa fa-file-csv"></i> Import Data Barang (CSV)</h5>
    </div>
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="file_csv" class="form-label">Pilih File CSV</label>
          <input type="file" name="file_csv" id="file_csv" class="form-control" accept=".csv" required>
        </div>
        <button type="submit" name="import" class="btn btn-success">
          <i class="fa fa-upload"></i> Import CSV
        </button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
