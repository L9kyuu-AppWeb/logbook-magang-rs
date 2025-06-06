<?php
$link = "$link_web/$req1";

// Supervisor ID (misalnya diset dari sesi login supervisor)
$supervisor_id = $_SESSION['supervisor_id'];

// Tampilkan daftar logbook supervisor
if (empty($req2)) {
    $sql = "SELECT l.*, 
            s.nama AS supervisor_nama, 
            k.nama AS karyawan_nama
        FROM Logbook l
        LEFT JOIN Supervisor s ON l.supervisor_id = s.supervisor_id
        LEFT JOIN Karyawan k ON l.karyawan_id = k.karyawan_id
        WHERE 
            l.supervisor_id = ? OR k.supervisor_id = ?
        ORDER BY 
            CASE 
                WHEN l.status = 'tunda' THEN 1
                WHEN l.status = 'belum' THEN 2
                ELSE 3
            END,
            l.tanggal DESC";
$dataLogbook = $db->queryBebas($sql, [$supervisor_id, $_SESSION['supervisor_id']]);


    ?>
<main class="p-6">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Logbook Supervisor</h2>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Karyawan</th>
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-left">Unit</th>
                    <th class="px-4 py-2 text-left">Aktivitas</th>
                    <th class="px-4 py-2 text-left">Waktu</th>
                    <th class="px-4 py-2 text-left">Hambatan</th>
                    <th class="px-4 py-2 text-left">Solusi</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Catatan Supervisor</th>
                    <th class="px-4 py-2 text-left">Bukti</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php foreach ($dataLogbook as $row): ?>
                <tr>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['karyawan_nama'] ?? 'Belum diisi' ) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['tanggal']) ?></td>
                    <td class="px-4 py-2">
                        <?= htmlspecialchars($db->lihatdata('Unit','nama_unit','unit_id = ?',[$row['unit_id']]) ?? 'Belum diisi') ?>
                    </td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['aktivitas']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['waktu_mulai']) ?> -
                        <?= htmlspecialchars($row['waktu_selesai']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['hambatan']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['solusi'] ?? 'Belum diisi') ?></td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-white text-xs
                    <?php
                        if ($row['status'] === 'belum') echo 'bg-gray-500';
                        elseif ($row['status'] === 'tunda') echo 'bg-yellow-500';
                        elseif ($row['status'] === 'selesai') echo 'bg-green-600';
                        else echo 'bg-red-600';
                    ?>">
                            <?= htmlspecialchars($row['status']) ?>
                        </span>
                    </td>

                    <td class="px-4 py-2"><?= htmlspecialchars($row['catatan_supervisor'] ?? 'Belum diisi') ?></td>
                    <td class="px-4 py-2">
                        <?php if (!empty($row['bukti_file'])): ?>
                        <a href="<?= $row['bukti_file'] ?>" target="_blank"
                            class="text-blue-600 underline text-xs">Lihat Bukti</a>
                        <?php else: ?>
                        <span class="text-gray-400 text-xs">-</span>
                        <?php endif ?>
                    </td>

                    <td class="px-4 py-2">
                        <a href="<?= "$link/ubah/$row[logbook_id]" ?>"
                            class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">Edit</a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</main>
<?php
} elseif ($req2 === 'ubah' && is_numeric($req3)) {
    // Ambil data logbook berdasarkan logbook_id dan supervisor_id
    $logbook = $db->tampilData('Logbook', [
        'where' => 'logbook_id = ?',
        'params' => [$req3]
    ]);

    if (empty($logbook)) {
        echo $db->alert('Logbook tidak ditemukan.', $link);
        exit;
    }
    $row = $logbook[0];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $solusi = $_POST['solusi'];
        $status = $_POST['status'];
        $catatan_supervisor = $_POST['catatan_supervisor'];

        // Update data logbook
        $update = $db->updateData('Logbook', [
            'solusi', 'status', 'catatan_supervisor', 'supervisor_id'
        ], 'logbook_id = ?', [$solusi, $status, $catatan_supervisor,$supervisor_id, $req3]);

        echo $db->alert($update ? 'Logbook berhasil diperbarui.' : 'Gagal memperbarui logbook.', $link);
        exit;
    }
    // Menggunakan fungsi lihatData untuk mendapatkan nama karyawan berdasarkan karyawan_id
    $karyawan_nama = $db->lihatData('Karyawan', 'nama', 'karyawan_id = ?', [$row['karyawan_id']]);
    ?>
<main class="p-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Edit Logbook Supervisor</h2>

    <!-- Menampilkan informasi logbook dalam bentuk cerita -->
    <div class="mb-6 text-gray-700">
        <p><strong>Nama Karyawan:</strong> <?= $karyawan_nama ?? 'Nama tidak ditemukan' ?></p>
        <p><strong>Tanggal:</strong> <?= date('d-m-Y', strtotime($row['tanggal'])) ?></p>
        <p><strong>Aktivitas:</strong> <?= $row['aktivitas'] ?></p>
        <p><strong>Waktu Mulai:</strong> <?= date('H:i', strtotime($row['waktu_mulai'])) ?></p>
        <p><strong>Waktu Selesai:</strong> <?= date('H:i', strtotime($row['waktu_selesai'])) ?></p>
        <p><strong>Hambatan:</strong> <?= $row['hambatan'] ?></p>
    </div>

    <!-- Form untuk mengedit logbook -->
    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-sm">Solusi</label>
            <textarea name="solusi" required class="border rounded px-3 py-2 w-full"><?= $row['solusi'] ?></textarea>
        </div>

        <div>
            <label class="block text-sm">Status</label>
            <select name="status" required class="border rounded px-3 py-2 w-full">
                <option value="belum" <?= $row['status'] === 'belum' ? 'selected' : '' ?>>Belum</option>
                <option value="tunda" <?= $row['status'] === 'tunda' ? 'selected' : '' ?>>Tunda</option>
                <option value="selesai" <?= $row['status'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
            </select>
        </div>

        <div>
            <label class="block text-sm">Catatan Supervisor</label>
            <textarea name="catatan_supervisor"
                class="border rounded px-3 py-2 w-full"><?= $row['catatan_supervisor'] ?></textarea>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Perbarui</button>
            <a href="<?= $link ?>" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali</a>
        </div>
    </form>
</main>

<?php
}else {
    echo $db->alert('Halaman tidak ditemukan.', $link);
}
?>