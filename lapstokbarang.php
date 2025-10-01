<?php include('koneksi.php');

$sql = "SELECT stock.namabarang, deskripsi, jmlmasuk, jmlkeluar FROM stock, keluar, masuk WHERE stock.idbarang = masuk.idbarang AND stock.idbarang = keluar.idbarang";
$query = mysqli_query($db, $sql);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Barang</title>
    <!-- Memuat CSS Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        .print-btn {
            margin-bottom: 20px;
        }

        @media print {

            .print-btn,
            h1 {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mb-4">Laporan Stok Barang Handphone</h1>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Nama Barang</th>
                    <th>Deskripsi</th>
                    <th>Stok Masuk</th>
                    <th>Stok Keluar</th>
                </tr>
            </thead>
            <tbody>
                <!-- Isi dengan data sesuai kebutuhan -->
                <?php
                $nomor = 1;
                while ($data = mysqli_fetch_array($query)) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $data['namabarang'] ?>
                        </td>
                        <td>
                            <?php echo $data['deskripsi'] ?>
                        </td>
                        <td>
                            <?php echo $data['jmlmasuk'] ?>
                        </td>
                        <td>
                            <?php echo $data['jmlkeluar'] ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="d-flex" style="padding: 10px;">
    <button class="btn btn-primary print-btn "style="margin-right:10px;" onclick="window.print()">Cetak Laporan</button>
    <button class="btn btn-primary text-decoration-none "style="height:40px;"><a href="beranda.php"
            class="text-decoration-none" style="color: white;">Kembali</a></button>
</div>


    </div>
    <!-- Memuat JavaScript Bootstrap -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>