<?php
// Mengambil tanggal minggu ini
$today = new DateTime();
$startOfWeek = $today->modify('monday this week')->format('Y-m-d');
$endOfWeek = $today->modify('saturday this week')->format('Y-m-d');

// Ambil total hari kerja (Senin-Jumat)
$totalHariKerja = 6;
$targetaktivitas = 24;

// Query untuk mencari logbook yang terisi oleh karyawan dalam minggu ini
$karyawan_id = $_SESSION['karyawan_id']; // ID karyawan, sesuaikan dengan data yang masuk
$query = "SELECT COUNT(*) FROM Logbook WHERE karyawan_id = ? AND tanggal BETWEEN ? AND ?";
$stmt = $db->pdo->prepare($query);
$stmt->execute([$karyawan_id, $startOfWeek, $endOfWeek]);
$hariTerisi = $stmt->fetchColumn();

// Hitung progress
$progress = ($hariTerisi / $targetaktivitas) * 100;

// Cari hari-hari yang belum terisi
$hariKerja = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$hariBelumTerisi = [];

for ($i = 0; $i < $totalHariKerja; $i++) {
    $currentDay = date('Y-m-d', strtotime("$startOfWeek +$i days"));
    $queryCheck = "SELECT COUNT(*) FROM Logbook WHERE karyawan_id = ? AND tanggal = ?";
    $stmtCheck = $db->pdo->prepare($queryCheck);
    $stmtCheck->execute([$karyawan_id, $currentDay]);
    if ($stmtCheck->fetchColumn() == 0) {
        $hariBelumTerisi[] = $hariKerja[$i];
    }
}

// Menampilkan data di halaman
?>

<main class="p-6">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Selamat Datang di Sistem Logbook Kompetensi Manajerial Rumah Sakit</h1>
        <p class="text-gray-600 mt-2">Pantau dan kelola aktivitas harian dengan efisien dan terorganisir.</p>
    </div>

    <!-- Progress Logbook Mingguan -->
    <div class="bg-white p-6 rounded-xl shadow mb-10">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Progress Logbook Mingguan</h2>
        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <p class="text-gray-700">Total hari kerja minggu ini: <span class="font-semibold"><?= $totalHariKerja ?></span></p>
            <p class="text-gray-700">Hari dengan logbook terisi: <span class="font-semibold text-green-600"><?= "$hariTerisi/$targetaktivitas" ?></span></p>
            <p class="text-gray-700">Progress: <span class="font-semibold text-blue-600"><?= number_format($progress, 2) ?>%</span></p>
        </div>

        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
            <div class="bg-blue-500 h-4" style="width: <?= $progress ?>%;"></div>
        </div>

        <div class="mt-4 text-yellow-600 font-medium">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?php if (empty($hariBelumTerisi)): ?>
                <span>Seluruh logbook untuk minggu ini sudah terisi!</span>
            <?php else: ?>
                Anda belum mengisi logbook hari <strong><?= implode('</strong>, <strong>', $hariBelumTerisi) ?></strong>. Segera lengkapi.
            <?php endif; ?>
        </div>
    </div>

    <!-- Kartu dan tabel lainnya bisa disambung di bawah sini -->
</main>
