<?php
session_start();

// Cek apakah pengguna sudah login, jika iya, redirect ke beranda.php
if (isset($_SESSION['login_user'])) {
    header("location: beranda.php");
    exit();
}

include("koneksi.php");

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM pengguna WHERE email = '$email' AND pasword = '$password'";
    $result = mysqli_query($db, $query);

    // Jika data pengguna ditemukan
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['login_user'] = $row['nama'];
        header("location: beranda.php");
        exit();
    } else {
        // Jika data pengguna tidak ditemukan
        $error = "Email atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Stok Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .left-side {
            flex: 1;
            padding: 30px;
            background-color: #f8f9fa;
            /* Warna background untuk sisi kiri */
        }

        .right-side {
            flex: 1;
            padding: 30px;
            background-color: #007bff;
            /* Warna background untuk sisi kanan */
            color: #fff;
            /* Warna teks pada sisi kanan */
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="left-side">
            <h2>Selamat datang di Sistem Stok Barang</h2>
            <p>Manajemen stok barang Anda menjadi lebih mudah dengan Sistem Stok Barang kami. Login sekarang untuk mengelola stok barang dengan efisien.</p>
        </div>
        <div class="right-side">
            <h2 class="mb-4">Login</h2>

            <?php
            // Menampilkan pesan error (jika ada)
            if (isset($error)) {
                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            }
            ?>

            <form action="proses_login.php" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>