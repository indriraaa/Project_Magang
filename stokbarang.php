<?php
include('cek_login.php');
include('koneksi.php');

$sql = "SELECT * FROM stock";
$query = mysqli_query($db, $sql);

?>
<?php
include("koneksi.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>Document</title>
</head>

<body>

    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Stok Barang</h2>
            <?php
            if (isset($_GET['status'])) :
                echo "<p>";
                if ($_GET['status'] == 'sukses_delete') {
                    echo "Data Sukses Dihapus !!! ";
                } elseif ($_GET['status'] == 'gagal') {
                    echo "Data Gagal Disimpan";
                }
                echo "</p>";
            endif;
            ?>
            <!-- <a href="tambahbarang.php" class="btn btn-secondary">Tambah Barang</a> -->
        </div>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Tanggal</th>
                    <th>Foto Produk</th>
                    <th>Jumlah Stok</th>
                    <th>Harga</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $nomor = 1;
                while ($data = mysqli_fetch_array($query)) { ?>
                    <tr>
                        <td>
                            <?php echo $nomor++ ?>
                        </td>
                        <td>
                            <?php echo $data['namabarang'] ?>
                        </td>
                        <td>
                            <?php echo $data['tanggal'] ?>
                        </td>
                        <td class="text-center">
                            <img src="img/<?php echo $data['foto'] ?>" alt="" class="" style="width:150px;" >
                        </td>
                        <td>
                            <?php echo $data['stock'] ?>
                        </td>
                        <td>
                            <?php echo number_format($data['harga'], 0, ',', '.') ?>
                        </td>
                        <td>
                            <a href="sunting.php?id=<?php echo $data['idbarang'] ?>"><i class="fa-regular fa-pen-to-square me-2" style="color:black ;"></i></a>
                            <a href="javascript:void(0);" onclick="konfirmasiHapus(<?php echo $data['idbarang'] ?>)"><i class="fa-solid fa-trash" style="color:black ;"></i></a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="beranda.php"><button class="btn btn-primary text-decoration-none">Kembali</button></a>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function konfirmasiHapus(id) {
            var konfirmasi = confirm("Apakah Anda yakin ingin menghapus data?");
            if (konfirmasi) {
                window.location.href = "hapus.php?id=" + id;
            }
        }
    </script>
</body>

</html>