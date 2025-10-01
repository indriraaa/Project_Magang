<?php
// Nama file CSV
$filename = "template_barang.csv";

// Set header agar browser mendownload file
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Buka output stream
$output = fopen('php://output', 'w');

// Tulis header kolom
fputcsv($output, ['Nama Barang', 'Jumlah']);

// Tambahkan catatan (akan tampil sebagai baris kedua)
fputcsv($output, [
    'Isi dengan nama barang',
    'Isi dengan angka jumlah barang'
]);

// Tutup stream
fclose($output);
exit;
?>
