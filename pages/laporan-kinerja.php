<?php
$link = "$link_web/$req1";

// Ambil admin_id yang login
$admin_id = $_SESSION['admin_id'];

if (empty($req2)) {
    $dataLaporan = $db->tampilData('LaporanKinerja', [
        'kolom' => 'laporan_id, tanggal_mulai, tanggal_selesai, total_aktivitas, total_hambatan, rekomendasi, laporan_file',
        'where' => 'admin_id = ?',
        'params' => [$admin_id],
        'orderBy' => 'tanggal_mulai DESC'
    ]);
?>
<main class="p-6">
    <div class="mb-4 flex justify-between gap-2">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Admin</h2>
        <a href="<?= $link ?>/tambah" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">+
            Tambah Laporan</a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Mulai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Selesai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Aktivitas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Hambatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rekomendasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">File</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (count($dataLaporan) === 0): ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada laporan.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($dataLaporan as $laporan): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($laporan['tanggal_mulai']) ?></td>
                    <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($laporan['tanggal_selesai']) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($laporan['total_aktivitas']) ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($laporan['total_hambatan']) ?></td>
                    <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($laporan['rekomendasi']) ?></td>
                    <td class="px-6 py-4 text-sm text-blue-600">
                        <?php if ($laporan['laporan_file']): ?>
                        <a href="<?= "$link_web/$laporan[laporan_file]" ?>" target="_blank" class="underline">Lihat File</a>
                        <?php else: ?>
                        <span class="text-gray-500 italic">Tidak ada</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-sm space-x-2">
                        <a href="<?= "$link/ubah/$laporan[laporan_id]" ?>"
                            class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500 text-xs">Edit</a>
                        <a href="<?= "$link/hapus/$laporan[laporan_id]" ?>"
                            onclick="return confirm('Yakin ingin menghapus laporan ini?')"
                            class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 text-xs">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
} elseif ($req2 === 'tambah') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tanggal_mulai = $_POST['tanggal_mulai'];
        $tanggal_selesai = $_POST['tanggal_selesai'];
        $total_aktivitas = $_POST['total_aktivitas'];
        $total_hambatan = $_POST['total_hambatan'];
        $rekomendasi = $_POST['rekomendasi'];
        $file_path = null;

        // Validasi dan unggah file PDF
        if (isset($_FILES['laporan_file']) && $_FILES['laporan_file']['error'] === 0) {
            $file_tmp = $_FILES['laporan_file']['tmp_name'];
            $file_name = basename($_FILES['laporan_file']['name']);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($file_ext !== 'pdf') {
                echo $db->alert('File harus berformat PDF.', "$link/tambah");
                exit;
            }

            $tujuan = "files/laporan/" . time() . "_$file_name";
            if (move_uploaded_file($file_tmp, $tujuan)) {
                $file_path = $tujuan;
            } else {
                echo $db->alert('Gagal mengunggah file.', "$link/tambah");
                exit;
            }
        }

        // Simpan ke database
        $simpan = $db->insertData('LaporanKinerja', 
            ['tanggal_mulai', 'tanggal_selesai', 'total_aktivitas', 'total_hambatan', 'rekomendasi', 'laporan_file', 'admin_id'], 
            [$tanggal_mulai, $tanggal_selesai, $total_aktivitas, $total_hambatan, $rekomendasi, $file_path, $admin_id]);

        if ($simpan) {
            echo $db->alert('Laporan berhasil ditambahkan!', $link);
        } else {
            echo $db->alert('Gagal menambahkan laporan.', $link);
        }
        exit;
    }
?>
<main class="p-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Tambah Laporan Kinerja</h2>
    <form action="<?= $link ?>/tambah" method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" required class="border rounded px-3 py-2 text-sm w-full" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" required class="border rounded px-3 py-2 text-sm w-full" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Total Aktivitas</label>
            <input type="number" name="total_aktivitas" required class="border rounded px-3 py-2 text-sm w-full" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Total Hambatan</label>
            <input type="number" name="total_hambatan" required class="border rounded px-3 py-2 text-sm w-full" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Rekomendasi</label>
            <textarea name="rekomendasi" required class="border rounded px-3 py-2 text-sm w-full"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Upload File (PDF)</label>
            <input type="file" name="laporan_file" accept="application/pdf"
                class="border rounded px-3 py-2 text-sm w-full" />
        </div>
        <div>
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Simpan</button>
            <a href="<?= $link ?>"
                class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 text-sm">Kembali</a>
        </div>
    </form>
</main>
<?php
} elseif ($req2 === 'ubah' and is_numeric($req3)) {
    $laporan_id = $req3;

    // Ambil data laporan berdasarkan laporan_id
    $laporanData = $db->tampilData('LaporanKinerja', [
        'kolom' => 'laporan_id, tanggal_mulai, tanggal_selesai, total_aktivitas, total_hambatan, rekomendasi, laporan_file',
        'where' => 'laporan_id = ? AND admin_id = ?',
        'params' => [$laporan_id, $admin_id]
    ]);

    if (count($laporanData) === 0) {
        echo $db->alert('Laporan tidak ditemukan atau Anda tidak memiliki akses untuk mengedit laporan ini.', $link);
        exit;
    }

    $laporanData = $laporanData[0]; // Ambil data laporan yang ditemukan

    // Proses update laporan jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tanggal_mulai = $_POST['tanggal_mulai'];
        $tanggal_selesai = $_POST['tanggal_selesai'];
        $total_aktivitas = $_POST['total_aktivitas'];
        $total_hambatan = $_POST['total_hambatan'];
        $rekomendasi = $_POST['rekomendasi'];
    
        // Ambil nama file laporan yang sudah ada
        $laporan_file = $laporanData['laporan_file']; // Default file yang sudah ada
    
        // Proses upload file PDF (jika ada)
        if (isset($_FILES['laporan_file']) && $_FILES['laporan_file']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['laporan_file']['tmp_name'];
            $file_name = $_FILES['laporan_file']['name'];
            $file_type = $_FILES['laporan_file']['type'];
    
            // Pastikan file yang diupload adalah PDF
            if ($file_type === 'application/pdf') {
                // Hapus file lama jika ada
                if (!empty($laporan_file) && file_exists($laporan_file)) {
                    unlink($laporan_file); // Hapus file lama
                }
    
                // Generate nama file baru secara acak
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION); // Mendapatkan ekstensi file
                $new_file_name = uniqid('laporan_', true) . '.' . $file_extension; // Nama acak dengan ekstensi asli
    
                $file_dir = 'files/laporan/';
                $file_path = $file_dir . $new_file_name; // Path file baru
    
                // Pindahkan file yang diupload ke folder tujuan
                if (move_uploaded_file($file_tmp, $file_path)) {
                    $laporan_file = $file_path; // Update dengan path file baru
                } else {
                    echo $db->alert('Gagal mengupload file.', $link);
                    exit;
                }
            } else {
                echo $db->alert('Hanya file PDF yang diperbolehkan!', $link);
                exit;
            }
        }
    
        // Update data ke database
        $update = $db->updateData('LaporanKinerja', 
            ['tanggal_mulai', 'tanggal_selesai', 'total_aktivitas', 'total_hambatan', 'rekomendasi', 'laporan_file'], 
            'laporan_id = ? AND admin_id = ?', 
            [$tanggal_mulai, $tanggal_selesai, $total_aktivitas, $total_hambatan, $rekomendasi, $laporan_file, $laporan_id, $admin_id]
        );
    
        if ($update) {
            echo $db->alert('Laporan berhasil diperbarui!', $link);
        } else {
            echo $db->alert('Gagal memperbarui laporan.', $link);
        }
        exit;
    }
    
?>

<main class="p-6">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Edit Laporan Kinerja</h2>
    </div>

    <form action="<?= $link ?>/ubah/<?= $laporan_id ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                value="<?= htmlspecialchars($laporanData['tanggal_mulai']) ?>"
                class="border rounded px-3 py-2 text-sm w-full" required />
        </div>

        <div>
            <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                value="<?= htmlspecialchars($laporanData['tanggal_selesai']) ?>"
                class="border rounded px-3 py-2 text-sm w-full" required />
        </div>

        <div>
            <label for="total_aktivitas" class="block text-sm font-medium text-gray-700">Total Aktivitas</label>
            <input type="number" name="total_aktivitas" id="total_aktivitas"
                value="<?= htmlspecialchars($laporanData['total_aktivitas']) ?>"
                class="border rounded px-3 py-2 text-sm w-full" required />
        </div>

        <div>
            <label for="total_hambatan" class="block text-sm font-medium text-gray-700">Total Hambatan</label>
            <input type="number" name="total_hambatan" id="total_hambatan"
                value="<?= htmlspecialchars($laporanData['total_hambatan']) ?>"
                class="border rounded px-3 py-2 text-sm w-full" required />
        </div>

        <div>
            <label for="rekomendasi" class="block text-sm font-medium text-gray-700">Rekomendasi</label>
            <textarea name="rekomendasi" id="rekomendasi" class="border rounded px-3 py-2 text-sm w-full"
                required><?= htmlspecialchars($laporanData['rekomendasi']) ?></textarea>
        </div>

        <div>
            <label for="laporan_file" class="block text-sm font-medium text-gray-700">File Laporan (PDF)</label>
            <input type="file" name="laporan_file" id="laporan_file" class="border rounded px-3 py-2 text-sm w-full"
                accept="application/pdf" />
            <span class="text-sm text-gray-500">*Biarkan kosong jika tidak ada perubahan file</span>
        </div>

        <div class="mt-4">
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Perbarui</button>
            <a href="<?= $link ?>"
                class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 text-sm">Kembali</a>
        </div>
    </form>
</main>
<?php
} elseif ($req2 === 'hapus' and is_numeric($req3)) {
    $laporan_id = $req3;

    // Ambil data laporan berdasarkan laporan_id
    $laporanData = $db->tampilData('LaporanKinerja', [
        'kolom' => 'laporan_id, tanggal_mulai, tanggal_selesai, total_aktivitas, total_hambatan, rekomendasi, laporan_file',
        'where' => 'laporan_id = ? AND admin_id = ?',
        'params' => [$laporan_id, $admin_id]
    ]);

    if (count($laporanData) === 0) {
        echo $db->alert('Laporan tidak ditemukan atau Anda tidak memiliki akses untuk mengedit laporan ini.', $link);
        exit;
    }

    $laporanData = $laporanData[0]; // Ambil data laporan yang ditemukan

    // Proses hapus laporan jika konfirmasi
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Menghapus file laporan jika ada
        if (!empty($laporanData['laporan_file']) && file_exists($laporanData['laporan_file'])) {
            unlink($laporanData['laporan_file']); // Hapus file
        }

        // Hapus data laporan dari database
        $delete = $db->deleteData('LaporanKinerja', 'laporan_id = ? AND admin_id = ?', [$laporan_id, $admin_id]);

        if ($delete) {
            echo $db->alert('Laporan berhasil dihapus!', $link);
        } else {
            echo $db->alert('Gagal menghapus laporan.', $link);
        }
        exit;
    }
?>

<main class="p-6">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Hapus Laporan Kinerja</h2>
    </div>

    <div class="bg-white p-4 shadow rounded-lg">
        <p class="text-sm text-gray-700">Anda yakin ingin menghapus laporan berikut?</p>

        <div class="mt-4">
            <p><strong>Tanggal Mulai:</strong> <?= htmlspecialchars($laporanData['tanggal_mulai']) ?></p>
            <p><strong>Tanggal Selesai:</strong> <?= htmlspecialchars($laporanData['tanggal_selesai']) ?></p>
            <p><strong>Total Aktivitas:</strong> <?= htmlspecialchars($laporanData['total_aktivitas']) ?></p>
            <p><strong>Total Hambatan:</strong> <?= htmlspecialchars($laporanData['total_hambatan']) ?></p>
            <p><strong>Rekomendasi:</strong> <?= htmlspecialchars($laporanData['rekomendasi']) ?></p>
            <p><strong>File Laporan:</strong> 
                <?php if ($laporanData['laporan_file']): ?>
                    <a href="<?= "$link_web/$laporanData[laporan_file]" ?>" target="_blank" class="underline">Lihat File</a>
                <?php else: ?>
                    <span class="text-gray-500 italic">Tidak ada file</span>
                <?php endif; ?>
            </p>
        </div>

        <div class="mt-6 flex space-x-4">
            <form action="<?= $link ?>/hapus/<?= $laporan_id ?>" method="POST">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm">Hapus</button>
            </form>
            <a href="<?= $link ?>" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 text-sm">Kembali</a>
        </div>
    </div>
</main>
<?php
}else {
    echo $db->alert('Halaman tidak ditemukan.', $link);
}
?>