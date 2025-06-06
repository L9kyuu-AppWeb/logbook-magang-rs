<?php
require '../databases/koneksi.php'; // Koneksi dan $db
session_start();

// Ambil filter tanggal dari session
$tanggal_mulai = $_SESSION['filter_tanggal_mulai'] ?? '';
$tanggal_akhir = $_SESSION['filter_tanggal_akhir'] ?? '';

// Validasi filter wajib diisi
if (empty($tanggal_mulai) || empty($tanggal_akhir)) {
    echo "<script>alert('Silakan lakukan filter tanggal terlebih dahulu.'); window.close();</script>";
    exit;
}

$tanggal_filter = "AND l.tanggal BETWEEN ? AND ?";
$params = [$tanggal_mulai, $tanggal_akhir];

// Ambil data logbook
$sql = "SELECT l.*, 
            s.nama AS supervisor_nama, 
            k.nama AS karyawan_nama
        FROM Logbook l
        LEFT JOIN Supervisor s ON l.supervisor_id = s.supervisor_id
        LEFT JOIN Karyawan k ON l.karyawan_id = k.karyawan_id
        WHERE l.status != 'belum' $tanggal_filter
        ORDER BY l.tanggal DESC";

$data = $db->queryBebas($sql, $params);

// Validasi jika tidak ada data
if (empty($data)) {
    echo "<script>alert('Data logbook tidak ditemukan pada rentang tanggal tersebut.'); window.close();</script>";
    exit;
}

// Format nama file: logbook_excel_2025-05-22_144530.csv
$filename = "logbook_excel_" . date("Y-m-d_His") . ".csv";

header('Content-Type: text/csv');
header("Content-Disposition: attachment; filename=\"$filename\"");


// Buat file CSV
$output = fopen('php://output', 'w');
fputcsv($output, ['No', 'Tanggal', 'Karyawan', 'Supervisor', 'Aktivitas', 'Waktu', 'Hambatan', 'Solusi', 'Status', 'Catatan']);

$no = 1;
foreach ($data as $row) {
    fputcsv($output, [
        $no++,
        $row['tanggal'],
        $row['karyawan_nama'],
        $row['supervisor_nama'],
        $row['aktivitas'],
        $row['waktu_mulai'] . ' - ' . $row['waktu_selesai'],
        $row['hambatan'],
        $row['solusi'],
        $row['status'],
        $row['catatan_supervisor']
    ]);
}
fclose($output);
exit;
