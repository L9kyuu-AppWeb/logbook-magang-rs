<?php
$link = "$link_web/$req1";

// Tangani filter tanggal melalui session
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tanggal_mulai'])) {
        $_SESSION['filter_tanggal_mulai'] = $_POST['tanggal_mulai'];
    }
    if (isset($_POST['tanggal_akhir'])) {
        $_SESSION['filter_tanggal_akhir'] = $_POST['tanggal_akhir'];
    }
    echo $db->alert2($link);
    exit;
}

// Ambil dari session jika tersedia
$tanggal_mulai = $_SESSION['filter_tanggal_mulai'] ?? '';
$tanggal_akhir = $_SESSION['filter_tanggal_akhir'] ?? '';

// Query semua logbook semua supervisor kecuali yang statusnya 'belum'
$tanggal_filter = '';
$params = [];
if ($tanggal_mulai && $tanggal_akhir) {
    $tanggal_filter = "AND l.tanggal BETWEEN ? AND ?";
    $params = [$tanggal_mulai, $tanggal_akhir];
}

$sql = "SELECT l.*, 
            s.nama AS supervisor_nama, 
            k.nama AS karyawan_nama
        FROM Logbook l
        LEFT JOIN Supervisor s ON l.supervisor_id = s.supervisor_id
        LEFT JOIN Karyawan k ON l.karyawan_id = k.karyawan_id
        WHERE l.status != 'belum' $tanggal_filter
        ORDER BY l.tanggal DESC";

$dataLogbook = $db->queryBebas($sql, $params);

$jumlahLogbook = count($dataLogbook);
$jumlahHambatan = 0;

foreach ($dataLogbook as $log) {
    $hambatan = strtolower(trim($log['hambatan']));
    if (!empty($hambatan) && $hambatan !== 'tidak ada' && $hambatan !== '-') {
        $jumlahHambatan++;
    }
}
?>

<main class="p-6">
    <div class="text-sm text-gray-600 mb-4">
        Total Logbook: <strong><?= $jumlahLogbook ?></strong> |
        Logbook dengan Hambatan: <strong><?= $jumlahHambatan ?></strong>
    </div>
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Logbook Admin</h2>
    </div>

    <!-- Form Filter Tanggal -->
    <form method="POST" class="mb-4">
        <div class="flex gap-4">
            <div>
                <label for="tanggal_mulai" class="block text-sm">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                    value="<?= htmlspecialchars($tanggal_mulai) ?>" class="border rounded px-3 py-2">
            </div>
            <div>
                <label for="tanggal_akhir" class="block text-sm">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                    value="<?= htmlspecialchars($tanggal_akhir) ?>" class="border rounded px-3 py-2">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
        </div>
    </form>

    <?php if (!empty($tanggal_mulai) && !empty($tanggal_akhir)): ?>
    <a href="prints/export_excel_logbook.php" target="_blank"
        class="inline-block mb-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
        Cetak ke Excel
    </a>
    <?php endif; ?>



    <!-- Tabel Logbook -->
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Karyawan</th>
                    <th class="px-4 py-2 text-left">Supervisor</th>
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-left">Aktivitas</th>
                    <th class="px-4 py-2 text-left">Waktu</th>
                    <th class="px-4 py-2 text-left">Hambatan</th>
                    <th class="px-4 py-2 text-left">Solusi</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Catatan Supervisor</th>
                    <th class="px-4 py-2 text-left">Bukti</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php foreach ($dataLogbook as $row): ?>
                <tr>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['karyawan_nama'] ?? '-') ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['supervisor_nama'] ?? '-') ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['tanggal']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['aktivitas']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['waktu_mulai']) ?> -
                        <?= htmlspecialchars($row['waktu_selesai']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['hambatan']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['solusi'] ?? 'Belum diisi') ?></td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-white text-xs
                            <?php
                            if ($row['status'] === 'tunda') echo 'bg-yellow-500';
                            elseif ($row['status'] === 'selesai') echo 'bg-green-600';
                            else echo 'bg-gray-500';
                            ?>">
                            <?= htmlspecialchars($row['status']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['catatan_supervisor'] ?? '-') ?></td>
                    <td class="px-4 py-2">
                        <?php if (!empty($row['bukti_file'])): ?>
                        <a href="<?= $row['bukti_file'] ?>" target="_blank"
                            class="text-blue-600 underline text-xs">Lihat Bukti</a>
                        <?php else: ?>
                        <span class="text-gray-400 text-xs">-</span>
                        <?php endif ?>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</main>