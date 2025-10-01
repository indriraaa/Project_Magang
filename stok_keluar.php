<?php
include('koneksi.php');
include('cek_login.php');
// Ambil data nama barang dari tabel stock
$sql_barang = "SELECT namabarang FROM stock";
$query_barang = mysqli_query($db, $sql_barang);
$barang_options = "";
while ($barang = mysqli_fetch_array($query_barang)) {
    $barang_options .= "<option value='" . $barang['namabarang'] . "'>" . $barang['namabarang'] . "</option>";
}
if (isset($_SESSION['notif'])) {
    echo '<div class="alert alert-success" role="alert">' . $_SESSION['notif'] . '</div>';
    unset($_SESSION['notif']); // Hapus notifikasi setelah ditampilkan
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Barang Keluar</title>
  <!-- Memuat CSS Bootstrap -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">
    <h2>Input Barang Keluar</h2>
    <form action="proses_tambahstokkeluar.php" method="post">
        <div class="form-group">
            <label for="namaBarang">Nama Barang:</label>
            <select name="idbarang" class="form-control" id="namaBarang">
                <?php
                // Fetch available products from the database
                $sql = "SELECT idbarang, namabarang FROM stock";
                $result = mysqli_query($db, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['idbarang']}'>{$row['namabarang']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="tanggal">Tanggal:</label>
            <input type="date" name="tanggal" class="form-control" id="tanggal" required>
        </div>
        <div class="form-group">
            <label for="stokBarang">Stok Keluar:</label>
            <input type="number" name="jmlkeluar" class="form-control" id="stokBarang" placeholder="Masukkan Jumlah Stok Barang" required>
        </div>
        <div class="form-group">
            <label for="penerima">Penerima:</label>
            <input type="text" name="penerima" class="form-control" id="penerima" placeholder="Masukkan Nama Penerima" required>
        </div>
        <input type="submit" name="tambah" value="Perbarui" class="btn btn-primary">
        <button class="btn btn-primary text-decoration-none"><a href="beranda.php" class="text-decoration-none"
                style="color: white;">Kembali</a></button>
    </form>
</div>

<!-- Memuat JavaScript Bootstrap (jika diperlukan) -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
