<?php
$link = "$link_web/$req1";

// Ambil logbook berdasarkan karyawan login
$karyawan_id = $_SESSION['karyawan_id'];
$link_excel = $db->lihatdata("Setting", "isian", "keterangan = ?", ['link_excel_kategori']);

// Tampilkan daftar logbook jika req2 kosong
if (empty($req2)) {
    $sql = "SELECT l.*, s.nama AS supervisor_nama, s.unit_id AS supervisor_unit_id
        FROM Logbook l
        LEFT JOIN Supervisor s ON l.supervisor_id = s.supervisor_id
        WHERE l.karyawan_id = ?
        ORDER BY 
            CASE 
                WHEN l.status = 'tunda' THEN 1
                WHEN l.status = 'belum' THEN 2
                ELSE 3
            END,
            l.tanggal DESC";


    $dataLogbook = $db->queryBebas($sql, [$karyawan_id]);
    ?>
<main class="p-6">
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Logbook Saya</h2>
        <a href="<?= $link ?>/tambah" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">
            + Isi Logbook
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-left">Unit</th>
                    <th class="px-4 py-2 text-left">Aktivitas</th>
                    <th class="px-4 py-2 text-left">Waktu</th>
                    <th class="px-4 py-2 text-left">Hambatan</th>
                    <th class="px-4 py-2 text-left">Solusi</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Catatan Supervisor</th>
                    <th class="px-4 py-2 text-left">Supervisor</th>
                    <th class="px-4 py-2 text-left">Bukti</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php foreach ($dataLogbook as $row): ?>
                <tr>
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
                    <td class="px-4 py-2"><?= htmlspecialchars($row['supervisor_nama'] ?? 'Belum diisi') ?></td>

                    <td class="px-4 py-2">
                        <?php if (!empty($row['bukti_file'])): ?>
                        <a href="<?= $row['bukti_file'] ?>" target="_blank"
                            class="text-blue-600 underline text-xs">Lihat Bukti</a>
                        <?php else: ?>
                        <span class="text-gray-400 text-xs">-</span>
                        <?php endif ?>
                    </td>

                    <td class="px-4 py-2">
                        <?php if ($row['status'] != 'selesai'): ?>
                        <a href="<?= "$link/ubah/$row[logbook_id]" ?>"
                            class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">Edit</a>
                        <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m0-6a2 2 0 012 2v2a2 2 0 01-2 2 2 2 0 01-2-2v-2a2 2 0 012-2zm0 0V9a4 4 0 118 0v3" />
                        </svg>
                        <?php endif; ?>

                        <?php if ($row['status'] === 'belum'): ?>
                        <a href="<?= "$link/hapus/$row[logbook_id]" ?>"
                            onclick="return confirm('Yakin ingin menghapus logbook ini?')"
                            class="bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700 ml-1">Hapus</a>
                        <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 text-gray-400 ml-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m0-6a2 2 0 012 2v2a2 2 0 01-2 2 2 2 0 01-2-2v-2a2 2 0 012-2zm0 0V9a4 4 0 118 0v3" />
                        </svg>
                        <?php endif; ?>
                    </td>

                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</main>
<?php
} elseif ($req2 === 'tambah') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tanggal = $_POST['tanggal'];
        $aktivitas = $_POST['aktivitas'];
        $waktu_mulai = $_POST['waktu_mulai'];
        $waktu_selesai = $_POST['waktu_selesai'];
        $hambatan = $_POST['hambatan'];
        $unit_id = $_POST['unit_id'];

        // Penanganan file bukti
        $bukti_file = $row['bukti_file'] ?? null; // Untuk edit
        if (isset($_FILES['bukti_file']) && $_FILES['bukti_file']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['application/pdf', 'image/jpeg', 'image/png'];
            $file_type = $_FILES['bukti_file']['type'];
            if (in_array($file_type, $allowed_types)) {
                $ext = pathinfo($_FILES['bukti_file']['name'], PATHINFO_EXTENSION);
                $random_name = uniqid('bukti_') . '.' . $ext;
                $upload_dir = 'files/bukti/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $file_path = $upload_dir . $random_name;

                // Hapus file lama (saat ubah)
                if (!empty($bukti_file) && file_exists($bukti_file)) {
                    unlink($bukti_file);
                }

                move_uploaded_file($_FILES['bukti_file']['tmp_name'], $file_path);
                $bukti_file = $file_path;
            } else {
                echo $db->alert('File harus berupa PDF, JPG, atau PNG!', $link);
                exit;
            }
        }

        $simpan = $db->insertData('Logbook', [
            'karyawan_id', 'tanggal', 'aktivitas', 'waktu_mulai', 'waktu_selesai', 'hambatan', 'bukti_file', 'unit_id'
        ], [$karyawan_id, $tanggal, $aktivitas, $waktu_mulai, $waktu_selesai, $hambatan, $bukti_file, $unit_id]);

        echo $db->alert($simpan ? 'Logbook berhasil ditambahkan.' : 'Gagal menyimpan logbook.', $link);
        exit;
    }
    
    $dataUnit = $db->tampilData('Unit', ['kolom' => 'unit_id, nama_unit']);
    ?>
<main class="p-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Isi Logbook Baru</h2>
    <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block text-sm">Tanggal</label>
            <input type="date" name="tanggal" required value="<?= date('Y-m-d') ?>"
                class="border rounded px-3 py-2 w-full" />
        </div>

        <div>
            <label class="block text-sm">Contoh Pengisian :
                <a href="<?= $link_excel ?>" target="_blank">Lihat Excel</a>
            </label>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Unit</label>
            <select name="unit_id" required class="w-full px-3 py-2 border rounded">
                <option value="">-- Pilih Unit --</option>
                <?php foreach ($dataUnit as $unit): ?>
                <option value="<?= $unit['unit_id'] ?>"><?= $unit['nama_unit'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm">Aktivitas</label>
            <textarea name="aktivitas" required class="border rounded px-3 py-2 w-full"></textarea>
        </div>
        <div class="flex gap-4">
            <div class="w-1/2">
                <label class="block text-sm">Waktu Mulai</label>
                <input type="time" name="waktu_mulai" required class="border rounded px-3 py-2 w-full" />
            </div>
            <div class="w-1/2">
                <label class="block text-sm">Waktu Selesai</label>
                <input type="time" name="waktu_selesai" required class="border rounded px-3 py-2 w-full" />
            </div>
        </div>
        <div>
            <label class="block text-sm mb-1">Hambatan</label>
            <label class="inline-flex items-center mb-2">
                <input type="checkbox" id="tidakAdaHambatan" class="mr-2">
                <span class="text-sm">Tidak ada hambatan</span>
            </label>
            <textarea name="hambatan" id="hambatanInput" class="border rounded px-3 py-2 w-full" required></textarea>
        </div>
        <div>
            <label class="block text-sm">Bukti (PDF/JPG/PNG)</label>
            <input type="file" name="bukti_file" accept=".pdf,.jpg,.jpeg,.png"
                class="border rounded px-3 py-2 w-full" />
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            <a href="<?= $link ?>" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali</a>
        </div>
    </form>
</main>
<?php
} elseif ($req2 === 'ubah' && is_numeric($req3)) {
    $logbook = $db->tampilData('Logbook', [
        'where' => 'logbook_id = ? AND karyawan_id = ?',
        'params' => [$req3, $karyawan_id]
    ]);
    if (empty($logbook)) {
        echo $db->alert('Logbook tidak ditemukan.', $link);
        exit;
    }
    $row = $logbook[0];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tanggal = $_POST['tanggal'];
        $aktivitas = $_POST['aktivitas'];
        $waktu_mulai = $_POST['waktu_mulai'];
        $waktu_selesai = $_POST['waktu_selesai'];
        $hambatan = $_POST['hambatan'];
        $unit_id = $_POST['unit_id'];

        // Penanganan file bukti
        $bukti_file = $row['bukti_file'] ?? null; // Untuk edit
        if (isset($_FILES['bukti_file']) && $_FILES['bukti_file']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['application/pdf', 'image/jpeg', 'image/png'];
            $file_type = $_FILES['bukti_file']['type'];
            if (in_array($file_type, $allowed_types)) {
                $ext = pathinfo($_FILES['bukti_file']['name'], PATHINFO_EXTENSION);
                $random_name = uniqid('bukti_') . '.' . $ext;
                $upload_dir = 'files/bukti/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $file_path = $upload_dir . $random_name;

                // Hapus file lama (saat ubah)
                if (!empty($bukti_file) && file_exists($bukti_file)) {
                    unlink($bukti_file);
                }

                move_uploaded_file($_FILES['bukti_file']['tmp_name'], $file_path);
                $bukti_file = $file_path;
            } else {
                echo $db->alert('File harus berupa PDF, JPG, atau PNG!', $link);
                exit;
            }
        }


        $update = $db->updateData('Logbook', [
            'tanggal', 'aktivitas', 'waktu_mulai', 'waktu_selesai', 'hambatan', 'bukti_file', 'unit_id'
        ], 'logbook_id = ?', [$tanggal, $aktivitas, $waktu_mulai, $waktu_selesai, $hambatan, $bukti_file, $unit_id, $req3]);

        echo $db->alert($update ? 'Logbook berhasil diperbarui.' : 'Gagal memperbarui logbook.', $link);
        exit;
    }
    ?>
<main class="p-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Edit Logbook</h2>
    <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block text-sm">Tanggal</label>
            <input type="date" name="tanggal" value="<?= $row['tanggal'] ?>" required
                class="border rounded px-3 py-2 w-full" />
        </div>

        <div>
            <label class="block text-sm">Contoh Pengisian :
                <a href="<?= $link_excel ?>">Lihat Excel</a>
            </label>
        </div>

        <div>
            <label for="unit_id" class="block text-sm font-medium text-gray-700">Unit</label>
            <select name="unit_id" id="unit_id" class="border rounded px-3 py-2 text-sm w-full" required>
                <option value="">Pilih Unit</option>
                <?php
            // Ambil data unit
            $unitData = $db->tampilData('Unit', ['kolom' => 'unit_id, nama_unit']);
            foreach ($unitData as $unit) {
                echo "<option value='{$unit['unit_id']}'" . 
                     ($unit['unit_id'] == $row['unit_id'] ? ' selected' : '') . 
                     ">{$unit['nama_unit']}</option>";
            }
        ?>
            </select>
        </div>


        <div>
            <label class="block text-sm">Aktivitas</label>
            <textarea name="aktivitas" required
                class="border rounded px-3 py-2 w-full"><?= $row['aktivitas'] ?></textarea>
        </div>
        <div class="flex gap-4">
            <div class="w-1/2">
                <label class="block text-sm">Waktu Mulai</label>
                <input type="time" name="waktu_mulai" value="<?= $row['waktu_mulai'] ?>" required
                    class="border rounded px-3 py-2 w-full" />
            </div>
            <div class="w-1/2">
                <label class="block text-sm">Waktu Selesai</label>
                <input type="time" name="waktu_selesai" value="<?= $row['waktu_selesai'] ?>" required
                    class="border rounded px-3 py-2 w-full" />
            </div>
            <div>
                <label class="block text-sm">Bukti (PDF/JPG/PNG)</label>
                <input type="file" name="bukti_file" accept=".pdf,.jpg,.jpeg,.png"
                    class="border rounded px-3 py-2 w-full" />
            </div>
        </div>
        <?php
        $is_tidak_ada = trim(strtolower($row['hambatan'])) === 'tidak ada';
        ?>
        <div>
            <label class="block text-sm mb-1">Hambatan</label>
            <label class="inline-flex items-center mb-2">
                <input type="checkbox" id="tidakAdaHambatan" class="mr-2" <?= $is_tidak_ada ? 'checked' : '' ?>>
                <span class="text-sm">Tidak ada hambatan</span>
            </label>
            <textarea name="hambatan" id="hambatanInput" class="border rounded px-3 py-2 w-full" required
                <?= $is_tidak_ada ? 'readonly' : '' ?>><?= htmlspecialchars($row['hambatan']) ?></textarea>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Perbarui</button>
            <a href="<?= $link ?>" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali</a>
        </div>
    </form>
</main>
<?php
}elseif ($req2 === 'hapus' && is_numeric($req3)) {
    // Ambil data logbook dulu untuk dapatkan file bukti
    $logbook = $db->tampilData('Logbook', [
        'where' => 'logbook_id = ? AND karyawan_id = ?',
        'params' => [$req3, $karyawan_id]
    ]);

    if (empty($logbook)) {
        echo $db->alert('Data tidak ditemukan.', $link);
        exit;
    }

    $bukti_path = $logbook[0]['bukti_file'];
    if (!empty($bukti_path) && file_exists($bukti_path)) {
        unlink($bukti_path);
    }

    $hapus = $db->hapusData('Logbook', 'logbook_id = ?', [$req3]);
    echo $db->alert($hapus ? 'Data berhasil dihapus.' : 'Gagal menghapus data.', $link);
    exit;
}else {
    echo $db->alert('Halaman tidak ditemukan.', $link);
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('tidakAdaHambatan');
    const textarea = document.getElementById('hambatanInput');

    function toggleHambatan() {
        if (checkbox.checked) {
            textarea.value = 'Tidak ada';
            textarea.readOnly = true;
        } else {
            if (textarea.value.trim().toLowerCase() === 'tidak ada') {
                textarea.value = '';
            }
            textarea.readOnly = false;
        }
    }

    checkbox.addEventListener('change', toggleHambatan);

    // Inisialisasi (misal saat reload halaman dengan data sebelumnya)
    toggleHambatan();
});
</script>