<?php
session_start();

// Jika sudah login, langsung redirect
if (isset($_SESSION['pengguna'])) {
    header("Location: beranda.php");
    exit();
}

$message = "";
$messageColor = "red";
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message']['text'];
    $messageColor = $_SESSION['flash_message']['color'];
    unset($_SESSION['flash_message']);
}

// Ambil pesan dari URL (untuk sukses register)
$msg = $_GET['msg'] ?? '';

// Simpan input lama (agar tidak hilang kalau ada error)
$old_email = $_SESSION['old']['email'] ?? '';
$old_role  = $_SESSION['old']['role'] ?? '';
unset($_SESSION['old']); // hapus setelah dipakai
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Form</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
body { font-family: 'Poppins', sans-serif; background: #f5f7f7; margin: 0; padding: 0; }
.navbar {
    background-color: #3AB0A2;
    padding: 25px 35px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #ffffff;
    font-size: 14px;
    font-weight:500;
}
.navbar-brand { font-weight: 500; font-size: 14px; color: #ffffff; font-family: 'Poppins', sans-serif; }
.nav-link { font-weight: 500; font-size: 16px; color: white; }
.navbar .d-flex i { font-size: 18px; color: #ffffff; letter-spacing: 1px; }

.main { flex: 1; display: flex; }
.left-side {
    flex: 1;
    background: #ffffff;
    display: flex;
    flex-direction: column;
    align-items: left;
    justify-content: center;
    padding: 30px;
}
.left-side img { width: 350px; margin-bottom: 100px; }
.right-side {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #9e9f9f, #eaf4f4);
}
.container-form {
    background:#fff;
    padding:35px 30px;
    border-radius:16px;
    box-shadow:0 8px 25px rgba(0,0,0,0.15);
    width:340px;
    text-align:center;
}
.form-header h2 { font-size:26px; font-weight:600; color:#2F4858; margin-bottom:20px; }

input[type="email"], input[type="password"], select {
    width: 100%; height: 45px; padding: 10px 12px; margin-bottom: 15px;
    border-radius: 10px; border: 1px solid #ccc; font-size: 14px;
    transition: .3s; box-sizing: border-box;
}
input[type="email"]:focus, input[type="password"]:focus, select:focus {
    border-color: #7FB7BE;
    box-shadow: 0 0 8px rgba(127,183,190,0.4);
    outline: none;
}
input[type="submit"], .btn-register {
    width: 100%; height: 45px; padding: 12px;
    border-radius: 10px; border: none;
    background: #7FB7BE; color: white;
    font-size: 15px; font-weight: 700;
    cursor: pointer; transition: 0.3s;
    margin-top: 10px; display: inline-block;
    text-decoration: none; box-sizing: border-box;
}
input[type="submit"]:hover, .btn-register:hover { background:#6AA9AF; }
.message { font-size:13px; margin-bottom:10px; }
</style>
</head>
<body>

<!-- Navbar -->
<div class="navbar d-flex justify-content-between align-items-center px-3">
    <span class="d-flex align-items-center gap-2">
        <i class="fa-solid fa-location-dot"></i>
        Direktorat Poltekkes Bandung, Jl. Padjajaran No 56 Bandung
    </span>
    <a href="kontak_us.php" class="d-flex align-items-center gap-2" style="color: white; text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='grey'" onmouseout="this.style.color='white'">
      <i class="fa-solid fa-phone"></i> Hubungi Kami
    </a>
</div>

<div class="main">
    <div class="left-side">
        <img src="img/Logo.png" alt="Logo Poltekkes">
        <img src="img/login.jpg" alt="Logo Login" style="width: 600px;">
    </div>

    <div class="right-side">
        <div class="container-form">
            <div class="form-header">
                <h2>Login</h2>
            </div>

            <?php if (!empty($message)) { ?>
                <p class="message" style="color: <?= $messageColor ?>;"><?= $message ?></p>
            <?php } ?>

            <form action="proses_login.php" method="post" class="form-login">
                <select name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" <?= ($old_role=='admin')?'selected':''; ?>>Admin</option>
                    <option value="pegawai" <?= ($old_role=='pegawai')?'selected':''; ?>>Pegawai</option>
                </select>
                <input type="email" name="email" placeholder="Masukan Email" value="<?= htmlspecialchars($old_email) ?>" required>
                <input type="password" name="password" placeholder="Masukan Password" required>
                <input type="submit" value="Login" name="Login">
            </form>

            <a href="register.php" class="btn-register">Register</a>
        </div>
    </div>
</div>

<?php if ($msg === 'success'): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Registrasi Berhasil!',
    text: 'Silakan login dengan akun Anda',
    confirmButtonText: 'OK'
});
</script>
<?php endif; ?>

</body>
</html>
