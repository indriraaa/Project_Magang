<?php
session_start();
include "koneksi.php"; // pastikan $db = mysqli_connect(...) berhasil

// ===== CEK LOGIN =====
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['pegawai', 'admin'])) {
    header("Location: index.php");
    exit();
}

$nama = $_SESSION['nama'] ?? '';
$nip  = $_SESSION['nip'] ?? '';
$kontak = $_SESSION['kontak'] ?? '';

// Kalau session nip/kontak kosong, ambil dari tabel pengguna (gunakan prepared statement)
if (empty($nip) || empty($kontak)) {
    if ($stmt = mysqli_prepare($db, "SELECT nip, kontak FROM pengguna WHERE nama = ? LIMIT 1")) {
        mysqli_stmt_bind_param($stmt, "s", $nama);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($res)) {
            $nip = $row['nip'];
            $kontak = $row['kontak'];
            $_SESSION['nip'] = $nip;
            $_SESSION['kontak'] = $kontak;
        }
        mysqli_stmt_close($stmt);
    }
}

// ===== HITUNG JUMLAH YANG DIPINJAM OLEH PEGAWAI LOGIN =====
$q_pinjam = mysqli_prepare($db, "SELECT SUM(jumlah) as total_pinjam FROM peminjaman WHERE nama_peminjam = ? AND status = 'Dipinjam'");
$total_pinjam_pegawai = 0;
if ($q_pinjam) {
    mysqli_stmt_bind_param($q_pinjam, "s", $nama);
    mysqli_stmt_execute($q_pinjam);
    $res = mysqli_stmt_get_result($q_pinjam);
    $r_pinjam = mysqli_fetch_assoc($res);
    $total_pinjam_pegawai = $r_pinjam['total_pinjam'] ?? 0;
    mysqli_stmt_close($q_pinjam);
}

// ===== HITUNG TOTAL STOK =====
$q_stok = mysqli_query($db, "SELECT SUM(jumlah) as total_stok FROM barang");
$r_stok = mysqli_fetch_assoc($q_stok);
$total_stok = $r_stok['total_stok'] ?? 0;

// ===== ENDPOINT GET_BARANG =====
if (isset($_GET['action']) && $_GET['action'] === 'get_barang') {
    header("Content-Type: application/json");

    $kd_barang = intval($_GET['kd_barang'] ?? 0);

    if ($kd_barang <= 0) {
        echo json_encode(["error" => "Kode barang tidak valid."]);
        exit;
    }

    $stmt = $db->prepare("SELECT kd_barang, nama_barang, jumlah, STATUS FROM barang WHERE kd_barang = ?");
    $stmt->bind_param("i", $kd_barang);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            "kd_barang"   => $row['kd_barang'],
            "nama_barang" => $row['nama_barang'],
            "jumlah"      => $row['jumlah'],
            "keterangan"  => $row['STATUS'] ?? ""
        ]);
    } else {
        echo json_encode(["error" => "Barang tidak ditemukan"]);
    }

    $stmt->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Form Peminjaman</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<style>
body { 
    font-family: 'Poppins', sans-serif; 
    background: #f5f7f7; 
    padding-bottom: 70px; /* ✅ Tambahkan agar footer tidak nutupin konten */
}

.navbar { 
    background-color: #3AB0A2; 
    padding: 12px; 
    color: white; 
    position: fixed; 
    top: 0; 
    width: 100%; 
    z-index: 1000; 
}
.navbar-brand { font-weight: 500; font-size: 14px; color: #ffffff; }
.nav-link.dropdown-toggle { 
  color: white
}

.qr-scan-btn {
    position: fixed; bottom: 90px; right: 25px;
    background: #3AB0A2; color: white; border-radius: 50%;
    width: 65px; height: 65px; display: flex; align-items: center; justify-content: center;
    font-size: 28px; box-shadow: 0 6px 12px rgba(0,0,0,0.2); transition: all 0.3s ease; z-index: 999;
}
.qr-scan-btn:hover { background: #2a8076; transform: scale(1.06) rotate(-4deg); }

input[readonly] { background: #f8f9fa; }
.card { border-radius: 12px; }

#qr-reader { width: 100%; max-width: 400px; margin: auto; position: relative; }
.qr-alert { position: absolute; top: 10px; left: 50%; transform: translateX(-50%); padding: 8px 16px; border-radius: 8px; font-weight: 500; color: #fff; display: none; z-index: 1000; }
.qr-alert.success { background-color: #28a745; }
.qr-alert.warning { background-color: #ffc107; color: #212529; }
.qr-alert.error { background-color: #dc3545; }

footer { 
    background-color: #8BC6BF; 
    color: #000000; 
    font-size: 14px; 
    position: fixed; 
    bottom: 0; 
    left: 0; 
    width:100%; 
    height: 60px; /* ✅ beri tinggi agar jelas */
    display: flex; 
    align-items: center; 
    justify-content: center;
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" style="font-family: 'Poppins', sans-serif; color: white; text-decoration: none;">
      <i class="fa-solid fa-location-dot"></i>
      Direktorat Poltekkes Bandung, Jl. Padjajaran No 56 Bandung
    </a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Akun</a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<!-- Wrapper Konten -->
<div class="container mt-5 pt-5" style="font-family: 'Poppins', sans-serif;">
  <div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-4">

      <!-- Judul Form -->
      <div class="mb-4 text-center">
        <h3 class="mb-2" style="font-weight:600; color:black;" >Form Peminjaman</h3>
      </div>

      <form method="post" action="proses_peminjaman.php">

        <!-- Data Peminjam -->
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <div class="form-floating">
              <input type="text" name="nama_peminjam" class="form-control" value="<?= htmlspecialchars($nama); ?>" readonly>
              <label>Nama Peminjam</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-floating">
              <input type="text" name="kontak" class="form-control" value="<?= htmlspecialchars($kontak); ?>" readonly>
              <label>Kontak</label>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-floating">
              <input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($nip); ?>" readonly>
              <label>NIP</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating">
              <input type="text" name="univ_jurusan" class="form-control" placeholder="Universitas / Jurusan" required>
              <label>Unit / Jurusan</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating">
              <input type="date" name="tanggal_pinjam" class="form-control" required>
              <label>Tanggal Pinjam</label>
            </div>
          </div>
        </div>

        <hr>

        <!-- Data Barang -->
        <h5 class="mt-3 mb-3" style="font-weight:500; color:#2c3e50;">📱 Barang yang Dipinjam</h5>
        <div id="barang-container">
          <div class="row g-3 barang-item mb-3">
            <div class="col-md-3">
              <div class="form-floating">
                <input type="text" name="kd_barang[]" class="form-control kd_barang" placeholder="Kode Barang" required>
                <label>Kode Barang</label>
                <!-- tambahan: hidden keterangan agar JS tidak error -->
                <input type="hidden" name="keterangan[]" class="keterangan" value="">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-floating">
                <input type="text" name="nama_barang[]" class="form-control nama_barang" placeholder="Nama Barang" readonly>
                <label>Nama Barang</label>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-floating">
                <input type="number" name="jumlah[]" class="form-control jumlah" min="1" placeholder="Jumlah" value="1" required>
                <label>Jumlah</label>
              </div>
            </div>
            <div class="col-md-1 d-flex align-items-center">
              <button type="button" class="btn btn-danger btn-sm remove-barang"><i class="fa fa-trash"></i></button>
            </div>
          </div>
        </div>

        <!-- Tombol Submit -->
        <div class="d-flex justify-content-between mt-4">
          <a href="beranda.php" class="btn btn-danger px-4 py-2">
            <i class="fa fa-arrow-left"></i> Kembali
          </a>
          <button type="submit" class="btn btn-success px-4 py-2">
            <i class="fa fa-save"></i> Simpan Peminjaman
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<!-- Modal QR Scanner -->
<div class="modal fade" id="qrModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-qrcode"></i> Pindai QR Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body position-relative">
        <div id="qr-reader"></div>
        <div id="qr-alert" class="qr-alert"></div>
      </div>
    </div>
  </div>
</div>

<!-- Tombol Pindai QR -->
<button type="button" class="qr-scan-btn" data-bs-toggle="modal" data-bs-target="#qrModal">
  <i class="fa-solid fa-qrcode"></i>
</button>

<script>
// Tambah baris baru
function tambahBarisBarang(prefill) {
  const container = document.getElementById("barang-container");
  const template = container.querySelector(".barang-item");
  const clone = template.cloneNode(true);

  // reset input -> number => 1, other inputs => ""
  clone.querySelectorAll("input").forEach(i => {
    if (i.type === 'number') i.value = 1;
    else i.value = "";
  });

  container.appendChild(clone);

  if (prefill) {
    const kdEl = clone.querySelector(".kd_barang");
    const namaEl = clone.querySelector(".nama_barang");
    const ketEl = clone.querySelector(".keterangan");
    const jumlahEl = clone.querySelector(".jumlah");

    if (kdEl) kdEl.value = prefill.kd ?? "";
    if (namaEl) namaEl.value = prefill.nama ?? "";
    if (ketEl) ketEl.value = prefill.keterangan ?? "";
    if (jumlahEl) jumlahEl.value = 1;
  }
  return clone;
}

// Hapus baris
document.addEventListener("click", e => {
  const btn = e.target.closest(".remove-barang");
  if (btn) {
    const item = btn.closest(".barang-item");
    const container = document.getElementById("barang-container");
    if (container.querySelectorAll(".barang-item").length > 1) {
      item.remove();
    } else {
      // kosongkan field jika hanya 1
      item.querySelectorAll("input").forEach(i => {
        if (i.type === 'number') i.value = 1;
        else i.value = "";
      });
    }
  }
});

// Fungsi alert kecil di QR
function showQRAlert(message, type='success', duration=2000) {
  const alertEl = document.getElementById("qr-alert");
  alertEl.textContent = message;
  alertEl.className = 'qr-alert ' + type;
  alertEl.style.display = 'block';
  setTimeout(() => { alertEl.style.display = 'none'; }, duration);
}

// === Inisialisasi QR Scanner ===
document.addEventListener("DOMContentLoaded", () => {
  const html5QrCode = new Html5Qrcode("qr-reader");
  let isScanning = false;
  let processingScan = false;

  const modal = document.getElementById("qrModal");

  modal.addEventListener("shown.bs.modal", () => {
    if (!isScanning) {
      Html5Qrcode.getCameras().then(devices => {
        if (devices && devices.length) {
          const cameraId = devices[0].id; // ambil kamera pertama
          html5QrCode.start(
            cameraId,
            { fps: 10, qrbox: 250 },
            qrCodeMessage => {
              // debounce agar tidak memproses banyak scan berurutan
              if (processingScan) return;
              processingScan = true;
              setTimeout(() => { processingScan = false; }, 1200);

              const kd_barang = String(qrCodeMessage).trim();
              if (!kd_barang) {
                showQRAlert("QR code kosong atau tidak sesuai format.", "error");
                return;
              }

              // cek duplikat pada form
              const exists = Array.from(document.querySelectorAll(".kd_barang"))
                .some(i => i.value === kd_barang);
              if (exists) {
                showQRAlert("Barang sudah ada!", "warning");
                return;
              }

              // panggil endpoint yang ada di halaman ini
const endpoint = window.location.origin + window.location.pathname +
  '?action=get_barang&kd_barang=' + encodeURIComponent(kd_barang);
              fetch(endpoint, { cache: 'no-store' })
                .then(res => {
                  if (!res.ok) throw new Error('Network response was not ok');
                  const ct = res.headers.get('content-type') || '';
                  if (ct.indexOf('application/json') !== -1) return res.json();
                  return res.text().then(t => { throw new Error('Invalid response: ' + t); });
                })
                .then(data => {
                  if (data.error) {
                    showQRAlert(data.error, "error");
                    return;
                  }

                  // cari baris kosong / buat baru
                  let emptyRowEl = Array.from(document.querySelectorAll(".kd_barang"))
                    .find(el => el.value.trim() === "");
                  if (!emptyRowEl) emptyRowEl = tambahBarisBarang().querySelector(".kd_barang");

                  const row = emptyRowEl.closest(".barang-item");
                  row.querySelector(".kd_barang").value = kd_barang;
                  const namaEl = row.querySelector(".nama_barang");
                  if (namaEl) namaEl.value = data.nama_barang ?? '';
                  const ketEl = row.querySelector(".keterangan");
                  if (ketEl) ketEl.value = data.keterangan ?? '';
                  const jumlahEl = row.querySelector(".jumlah");
                  if (jumlahEl) jumlahEl.value = 1;

                  // get user feedback
                  if (navigator.vibrate) navigator.vibrate(80);
                  showQRAlert((data.nama_barang ?? kd_barang) + " berhasil ditambahkan.", "success");
                })
                .catch(err => {
                  console.error(err);
                  showQRAlert("Gagal ambil data barang", "error");
                });
            },
            errorMessage => {
              // opsi: tampilkan debug jika perlu
              // console.debug("Scan error:", errorMessage);
            }
          ).then(() => {
            isScanning = true;
          }).catch(err => {
            console.error("Start camera failed", err);
            showQRAlert("Tidak dapat mengakses kamera", "error");
          });
        } else {
          showQRAlert("Tidak ditemukan kamera pada perangkat.", "error");
        }
      }).catch(err => {
        console.error("getCameras error", err);
        showQRAlert("Tidak dapat mengakses kamera", "error");
      });
    }
  });

  modal.addEventListener("hidden.bs.modal", () => {
    if (isScanning) {
      html5QrCode.stop().catch(err => console.error("Stop scan error", err));
      isScanning = false;
    }
    // sembunyikan alert QR jika masih tampil
    const alertEl = document.getElementById("qr-alert");
    if (alertEl) alertEl.style.display = 'none';
  });
});
</script>

<!-- Footer -->
<footer class="text-center py-3 mt-auto">
  <p class="mb-0">&copy; <?= date('Y'); ?> Direktorat Poltekkes Bandung</p>
</footer>

</body>
</html>
