<?php
// membuat link untuk halaman ini
$link = "$link_web/$req1";
// Cek apakah ada nilai req2 di URL
if (empty($req2)) {
    // Jika req2 kosong, tampilkan tabel unit

    // Ambil nilai pencarian dari session
    $cari = $_SESSION['cari_unit'] ?? '';

    // Update session jika ada pencarian baru
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cari'])) {
        $_SESSION['cari_unit'] = $_POST['cari'];
        $cari = $_POST['cari'];
        echo $db->alert2($link);
    } 

    // menghapus nilai pencarian
    if ($req3 == "reset") {
        unset($_SESSION['cari_unit']);
        $cari = '';
        echo $db->alert2($link);
    }

    // Ambil data unit dari database
    if ($cari != '') {
        $dataUnit = $db->tampilData('Unit', [
            'kolom' => 'unit_id, nama_unit',
            'where' => 'nama_unit LIKE ?',
            'params' => ["%$cari%"],
            'orderBy' => 'unit_id ASC'
        ]);
    } else {
        $dataUnit = $db->tampilData('Unit', [
            'kolom' => 'unit_id, nama_unit',
            'orderBy' => 'unit_id ASC'
        ]);
    }

    // Tampilkan tabel unit
    ?>
    <main class="p-6">
        <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Unit</h2>
                <a href="<?= $link ?>/tambah" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">
                    + Tambah Unit
                </a>
            </div>
            <form method="POST" class="flex gap-2">
                <input type="text" name="cari" value="<?= htmlspecialchars($cari) ?>" placeholder="Cari nama unit..." class="border rounded px-3 py-1 text-sm" />
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">Cari</button>
                <a href="<?= "$link//reset" ?>" class="bg-gray-400 text-white px-3 py-1 rounded hover:bg-gray-500 text-sm">Reset</a>
            </form>
        </div>

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (count($dataUnit) === 0): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">Tidak ada data unit.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dataUnit as $row): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($row['unit_id']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($row['nama_unit']) ?></td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="<?= "$link/ubah/$row[unit_id]" ?>" class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500 text-xs">Edit</a>
                                    <a href="<?= "$link/hapus/$row[unit_id]" ?>" onclick="return confirm('Yakin ingin menghapus unit ini?')" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 text-xs">Hapus</a>
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
    // Jika req2 adalah "tambah", tampilkan form tambah unit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama_unit = trim($_POST['nama_unit']);
        $simpan = $db->insertData('Unit', ['nama_unit'], [$nama_unit]);

        if ($simpan) {
            echo $db->alert('Unit berhasil ditambahkan!', $link);
        } else {
            echo $db->alert('Gagal menambahkan unit.', $link);
        }
        exit;
    }
    ?>
    <main class="p-6">
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Tambah Unit</h2>
        </div>

        <form action="<?= $link ?>/tambah" method="POST" class="space-y-4">
            <div>
                <label for="nama_unit" class="block text-sm font-medium text-gray-700">Nama Unit</label>
                <input type="text" name="nama_unit" id="nama_unit" class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan nama unit" required />
            </div>
            
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Simpan</button>
                <a href="<?= $link ?>" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 text-sm">Kembali</a>
            </div>
        </form>
    </main>
    <?php
} elseif ($req2 === 'ubah' && is_numeric($req3)) {
    // Jika req2 adalah "ubah" dan ada unit_id di req3, tampilkan form edit unit
    $unit_id = $req3;

    // Ambil data unit berdasarkan unit_id
    $unitData = $db->tampilData('Unit', [
        'kolom' => 'unit_id, nama_unit',
        'where' => 'unit_id = ?',
        'params' => [$unit_id]
    ]);

    if (count($unitData) === 0) {
        echo $db->alert('Unit tidak ditemukan.', $link);
        exit;
    }

    $unitData = $unitData[0];

    // Proses update unit jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama_unit = $_POST['nama_unit'];

        // Update data ke database
        $update = $db->updateData('Unit', ['nama_unit'], 'unit_id = ?', [$nama_unit, $unit_id]);
        if ($update) {
            echo $db->alert('Unit berhasil diperbarui!', $link);
        } else {
            echo $db->alert('Gagal memperbarui unit.', $link);
        }
    }

    // Tampilkan form edit unit
    ?>
    <main class="p-6">
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Edit Unit</h2>
        </div>

        <form action="<?= $link ?>/ubah/<?= $unit_id ?>" method="POST" class="space-y-4">
            <div>
                <label for="nama_unit" class="block text-sm font-medium text-gray-700">Nama Unit</label>
                <input type="text" name="nama_unit" id="nama_unit" value="<?= htmlspecialchars($unitData['nama_unit']) ?>" class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan nama unit" required />
            </div>
            
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Perbarui</button>
                <a href="<?= $link ?>" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 text-sm">Kembali</a>
            </div>
        </form>
    </main>
    <?php
} elseif ($req2 === 'hapus' && is_numeric($req3)) {
    $unit_id = $req3;

    // Menghapus data unit berdasarkan unit_id
    $delete = $db->deleteData('Unit', 'unit_id = ?', [$unit_id]);

    if ($delete) {
        echo $db->alert('Unit berhasil dihapus!', $link);
    } else {
        echo $db->alert('Gagal menghapus unit.', $link);
    }
} else {
    echo $db->alert('Halaman tidak ditemukan.', $link);
}
?>
