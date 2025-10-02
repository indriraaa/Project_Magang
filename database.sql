-- Membuat database
CREATE DATABASE IF NOT EXISTS stokbarang;
USE stokbarang;

-- Tabel barang
CREATE TABLE IF NOT EXISTS barang (
    kd_barang BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama_barang VARCHAR(100) NOT NULL,
    jumlah INT NOT NULL,
    STATUS VARCHAR(100) NULL
);


-- Tabel peminjaman
CREATE TABLE IF NOT EXISTS peminjaman (
    NO INT PRIMARY KEY AUTO_INCREMENT,
    kd_barang BIGINT,
    nama_barang VARCHAR(100) NULL,
    nama_peminjam VARCHAR(100) NOT NULL,
    nip BIGINT NULL,
    jumlah INT NOT NULL,
    univ_jurusan VARCHAR(50) NULL,
    tanggal_pinjam DATE NOT NULL,
    tanggal_kembali DATE NULL,
    STATUS ENUM('Dipinjam', 'Dikembalikan') DEFAULT 'Dipinjam',
    CONSTRAINT fk_peminjaman_barang 
        FOREIGN KEY (kd_barang) REFERENCES barang(kd_barang)
);

-- Tabel pengguna
CREATE TABLE IF NOT EXISTS pengguna (
    NO INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    nip BIGINT NULL,
    kontak BIGINT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    PASSWORD VARCHAR(255) NOT NULL,
    ROLE VARCHAR(50) NOT NULL
);

-- Reset penomoran pengguna (jika sudah ada data)
SET @num := 0;
UPDATE pengguna SET NO = @num := @num + 1 ORDER BY NO;
ALTER TABLE pengguna AUTO_INCREMENT = 1;

-- Tabel stok keluar
CREATE TABLE IF NOT EXISTS stok_keluar (
    NO INT AUTO_INCREMENT PRIMARY KEY,
    kd_barang BIGINT NOT NULL,
    jumlah INT NOT NULL,
    tanggal_keluar DATE DEFAULT NULL,
    CONSTRAINT fk_stok_keluar_barang 
        FOREIGN KEY (kd_barang) REFERENCES barang (kd_barang) ON DELETE CASCADE
) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- Tabel stok masuk
CREATE TABLE IF NOT EXISTS stok_masuk (
    NO INT AUTO_INCREMENT PRIMARY KEY,
    kd_barang BIGINT NOT NULL,
    jumlah INT NOT NULL,
    tanggal_masuk DATE DEFAULT NULL,
    CONSTRAINT fk_stok_masuk_barang 
        FOREIGN KEY (kd_barang) REFERENCES barang (kd_barang) ON DELETE CASCADE
) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

ALTER TABLE barang AUTO_INCREMENT = 2025080901;
SELECT * FROM barang WHERE kd_barang = 2025080932;
