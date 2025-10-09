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

.main { flex: 1; display: flex; flex-wrap: wrap; } /* tambahkan flex-wrap agar responsif */
.left-side {
    flex: 1 1 100%;
    background: #ffffff;
    display: flex;
    flex-direction: column;
    align-items: left;
    justify-content: center;
    padding: 30px;
}
.left-side img { width: 100%; max-width: 350px; margin-bottom: 30px; }
.right-side {
    flex: 1 1 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #9e9f9f, #eaf4f4);
    padding: 20px;
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

/* --- Responsif tambahan --- */
@media screen and (min-width: 769px) {
  .main {
    flex-wrap: nowrap;
  }
  .left-side, .right-side {
    flex: 1;
    min-height: 100vh;
  }
  .left-side img:first-child {
    max-width: 350px;
  }
  .left-side img:last-child {
    max-width: 600px;
  }
}
</style>
</head>
<body>

<!-- Navbar -->
<div class="navbar d-flex justify-content-between align-items-center px-3">
    <span class="d-flex align-items-center gap-2">
        <i class="fa-solid fa-location-dot"></i>
        Direktorat Poltekkes Bandung, Jl. Padjajaran No 56 Bandung
    </span>
    <a href="kontak_us.html" class="d-flex align-items-center gap-2" style="color: white; text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='grey'" onmouseout="this.style.color='white'">
      <i class="fa-solid fa-phone"></i> Hubungi Kami
    </a>
</div>

<div class="main">
    <div class="left-side">
        <img src="img/Logo.png" alt="Logo Poltekkes">
        <img src="img/login.jpg" alt="Logo Login">
    </div>

    <div class="right-side">
        <div class="container-form">
            <div class="form-header">
                <h2>Login</h2>
            </div>

            <form action="proses_login.php" method="post" class="form-login">
                <select name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Admin</option>
                    <option value="pegawai">Pegawai</option>
                </select>
                <input type="email" name="email" placeholder="Masukan Email" required>
                <input type="password" name="password" placeholder="Masukan Password" required>
                <input type="submit" value="Login" name="Login">
            </form>

            <a href="register.html" class="btn-register">Register</a>
        </div>
    </div>
</div>

</body>
</html>
