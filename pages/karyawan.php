<?php
// Membuat link untuk halaman ini
$link = "$link_web/$req1";
// Menampilkan daftar supervisor
if (empty($req2)) {

// Tangani pencarian
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cari_karyawan'])) {
        $_SESSION['cari_karyawan'] = trim($_POST['cari']);
    } elseif (isset($_POST['reset_cari'])) {
        unset($_SESSION['cari_karyawan']);
    }

    // Reload agar URL tetap bersih (hindari resubmit form)
    echo $db->alert2($link);
    exit;
}

// Ambil keyword dari session
$cari = isset($_SESSION['cari_karyawan']) ? $_SESSION['cari_karyawan'] : '';

// SQL: join ke Pengguna & Supervisor
$sql = "
    SELECT 
        k.karyawan_id,
        k.nama,
        k.supervisor_id,
        u.nama as nama_supervisor,
        k.posisi,
        k.email,
        k.no_telp,
        k.tanggal_masuk,
        k.status_kerja,
        p.username
    FROM Karyawan k
    LEFT JOIN Pengguna p ON k.karyawan_id = p.karyawan_id
    LEFT JOIN Supervisor u ON k.supervisor_id = u.supervisor_id
";

// Tambah WHERE jika ada pencarian
$params = [];
if ($cari !== '') {
    $sql .= " WHERE k.nama LIKE ? OR k.posisi LIKE ? OR p.username LIKE ? OR u.nama LIKE ?";
    $params = ["%$cari%", "%$cari%", "%$cari%", "%$cari%"];
}

// Eksekusi query
$dataKaryawan = $db->queryBebas($sql, $params);
?>

<main class="p-6">
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Karyawan</h2>
        <a href="<?= $link ?>/tambah" class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">+
            Tambah Karyawan</a>
    </div>

    <form method="POST" class="mb-4 flex gap-2 items-center">
        <input type="text" name="cari" placeholder="Cari karyawan..." value="<?= htmlspecialchars($cari) ?>"
            class="border rounded px-3 py-2 w-full" />
        <button type="submit" name="cari_karyawan" class="bg-blue-600 text-white px-4 py-2 rounded">Cari</button>
        <button type="submit" name="reset_cari" class="bg-gray-500 text-white px-4 py-2 rounded">Reset</button>
    </form>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Posisi</th>
                    <th class="px-4 py-2 text-left">Supervisor</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">No. Telp</th>
                    <th class="px-4 py-2 text-left">Tanggal Masuk</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Username</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($dataKaryawan) === 0): ?>
                <tr>
                    <td colspan="9" class="px-4 py-2 text-center text-gray-500">Data tidak ditemukan.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($dataKaryawan as $row): ?>
                <tr class="border-t">
                    <td class="px-4 py-2"><?= htmlspecialchars($row['nama']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['posisi']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['nama_supervisor'] ?? '-') ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['email']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['no_telp']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['tanggal_masuk']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['status_kerja']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($row['username'] ?? '-') ?></td>
                    <td class="px-4 py-2">
                        <a href="<?= $link ?>/ubah/<?= $row['karyawan_id'] ?>"
                            class="text-blue-600 hover:underline">Edit</a> |
                        <a href="<?= $link ?>/hapus/<?= $row['karyawan_id'] ?>"
                            onclick="return confirm('Yakin ingin menghapus?')"
                            class="text-red-600 hover:underline">Hapus</a>
                    </td>
                </tr>
                <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</main>
<?php 
} elseif ($req2 === 'tambah') {
    // Proses jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama            = trim($_POST['nama']);
        $supervisor_id   = $_POST['supervisor_id'];
        $posisi          = trim($_POST['posisi']);
        $email           = trim($_POST['email']);
        $no_telp         = trim($_POST['no_telp']);
        $tanggal_masuk   = $_POST['tanggal_masuk'];
        $status_kerja    = $_POST['status_kerja'];
        $username        = trim($_POST['username']);
        $password        = trim($_POST['password']);

        if (!$nama || !$supervisor_id || !$posisi || !$email || !$username || !$password) {
            echo $db->alert("Semua field wajib diisi!", $link);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo $db->alert("Format email tidak valid!", $link);
            exit;
        }

        try {
            $db->pdo->beginTransaction();

            // Insert ke tabel Karyawan
            $fieldsKaryawan = ['nama', 'supervisor_id', 'posisi', 'email', 'no_telp', 'tanggal_masuk', 'status_kerja'];
            $valuesKaryawan = [$nama, $supervisor_id, $posisi, $email, $no_telp, $tanggal_masuk, $status_kerja];

            $db->insertData('Karyawan', $fieldsKaryawan, $valuesKaryawan);
            $karyawan_id = $db->pdo->lastInsertId();

            // Insert ke tabel Pengguna (role: karyawan)
            $fieldsPengguna = ['username', 'password', 'role', 'karyawan_id'];
            $valuesPengguna = [$username, password_hash($password, PASSWORD_DEFAULT), 'karyawan', $karyawan_id];

            $db->insertData('Pengguna', $fieldsPengguna, $valuesPengguna);

            $db->pdo->commit();
            echo $db->alert("Data karyawan & akun berhasil ditambahkan!", $link);
        } catch (PDOException $e) {
            $db->pdo->rollBack();
            echo $db->alert("Gagal menyimpan data: " . $e->getMessage(), $link);
        }

        exit;
    }

    // Ambil data unit untuk dropdown
    $dataSupervisor = $db->tampilData('Supervisor', ['kolom' => 'supervisor_id, nama']);
    ?>

<main class="p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Tambah Karyawan</h2>
    <form action="<?= $link ?>/tambah" method="POST" class="space-y-4">

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" name="nama" required class="w-full px-3 py-2 border rounded" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Supervisor</label>
            <select name="supervisor_id" required class="w-full px-3 py-2 border rounded">
                <option value="">-- Pilih Supervisor --</option>
                <?php foreach ($dataSupervisor as $supervisor): ?>
                <option value="<?= $supervisor['supervisor_id'] ?>"><?= $supervisor['nama'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Posisi</label>
            <input type="text" name="posisi" required class="w-full px-3 py-2 border rounded" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" required class="w-full px-3 py-2 border rounded" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
            <input type="text" name="no_telp" class="w-full px-3 py-2 border rounded" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" required class="w-full px-3 py-2 border rounded" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Status Kerja</label>
            <select name="status_kerja" class="w-full px-3 py-2 border rounded">
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>

        <div class="border-t pt-4">
            <h3 class="font-semibold text-gray-700">Akun Pengguna</h3>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" required class="w-full px-3 py-2 border rounded" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required class="w-full px-3 py-2 border rounded" />
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            <a href="<?= $link ?>" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali</a>
        </div>
    </form>
</main>
<?php
}elseif ($req2 === 'ubah' && is_numeric($req3)) {
    // Ambil karyawan_id dari req3
    $karyawan_id = $req3;

    // Ambil data karyawan berdasarkan karyawan_id
    $karyawanData = $db->tampilData('Karyawan', [
        'kolom' => 'karyawan_id, nama, supervisor_id, posisi, email, no_telp, tanggal_masuk, status_kerja',
        'where' => 'karyawan_id = ?',
        'params' => [$karyawan_id]
    ]);

    if (count($karyawanData) === 0) {
        echo $db->alert('Karyawan tidak ditemukan.', $link);
        exit;
    }

    $karyawanData = $karyawanData[0];

    // Ambil data pengguna terkait karyawan_id
    $penggunaData = $db->tampilData('Pengguna', [
        'kolom' => 'pengguna_id, username, password, role, karyawan_id',
        'where' => 'karyawan_id = ?',
        'params' => [$karyawan_id]
    ]);

    if (count($penggunaData) === 0) {
        echo $db->alert('Pengguna tidak ditemukan.', $link);
        exit;
    }

    $penggunaData = $penggunaData[0];

    // Proses update data jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ambil data dari form dan sanitasi
        $nama = trim($_POST['nama']);
        $supervisor_id = $_POST['supervisor_id'];
        $posisi = trim($_POST['posisi']);
        $email = trim($_POST['email']);
        $no_telp = trim($_POST['no_telp']);
        $status_kerja = $_POST['status_kerja'];
        
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);  // Password baru (bisa kosong)

        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo $db->alert('Format email tidak valid.', $link);
            exit;
        }

        // Validasi username
        if (empty($nama) || empty($email) || empty($posisi) || empty($username) || empty($status_kerja)) {
            echo $db->alert('Nama, Email, Posisi, Username dan Status Kerja tidak boleh kosong.', $link);
            exit;
        }

        try {
            // Mulai transaksi
            $db->pdo->beginTransaction();

            // Update data karyawan
            $updateKaryawan = $db->updateData('Karyawan', ['nama', 'supervisor_id', 'posisi', 'email', 'no_telp', 'status_kerja'], 'karyawan_id = ?', [
                $nama, $supervisor_id, $posisi, $email, $no_telp, $status_kerja, $karyawan_id
            ]);

            // Update data pengguna (username dan password)
            if (!empty($password)) {
                // Jika password diisi, update password juga
                $updatePengguna = $db->updateData('Pengguna', ['username', 'password'], 'karyawan_id = ?', [
                    $username, password_hash($password, PASSWORD_DEFAULT), $karyawan_id
                ]);
            } else {
                // Jika password kosong, hanya update username
                $updatePengguna = $db->updateData('Pengguna', ['username'], 'karyawan_id = ?', [$username, $karyawan_id]);
            }

            // Cek apakah kedua operasi berhasil
            if ($updateKaryawan && $updatePengguna) {
                // Commit jika semua berhasil
                $db->pdo->commit();
                echo $db->alert('Data Karyawan dan Pengguna berhasil diperbarui!', $link);
            } else {
                // Rollback jika ada yang gagal
                $db->pdo->rollBack();
                echo $db->alert('Gagal memperbarui data Karyawan atau Pengguna.', $link);
            }

        } catch (PDOException $e) {
            // Rollback jika ada error
            $db->pdo->rollBack();
            echo $db->alert('Terjadi kesalahan: ' . $e->getMessage(), $link);
        }
    }

    // Tampilkan form edit karyawan dan pengguna
    ?>
<main class="p-6">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Edit Data Karyawan dan Pengguna</h2>
    </div>

    <form action="<?= $link ?>/ubah/<?= $karyawan_id ?>" method="POST" class="space-y-4">
        <!-- Form Karyawan -->
        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($karyawanData['nama']) ?>"
                class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan nama karyawan" required />
        </div>

        <div>
            <label for="supervisor_id" class="block text-sm font-medium text-gray-700">Supervisor</label>
            <select name="supervisor_id" id="supervisor_id" class="border rounded px-3 py-2 text-sm w-full" required>
                <option value="">Pilih Supervisor</option>
                <?php
                    // Ambil data unit
                    $unitData = $db->tampilData('Supervisor', ['kolom' => 'supervisor_id, nama']);
                    foreach ($unitData as $unit) {
                        echo "<option value='{$unit['supervisor_id']}'" . ($unit['supervisor_id'] == $karyawanData['supervisor_id'] ? ' selected' : '') . ">{$unit['nama']}</option>";
                    }
                    ?>
            </select>
        </div>

        <div>
            <label for="posisi" class="block text-sm font-medium text-gray-700">Posisi</label>
            <input type="text" name="posisi" id="posisi" value="<?= htmlspecialchars($karyawanData['posisi']) ?>"
                class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan posisi karyawan" required />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($karyawanData['email']) ?>"
                class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan email karyawan" required />
        </div>

        <div>
            <label for="no_telp" class="block text-sm font-medium text-gray-700">No Telepon</label>
            <input type="text" name="no_telp" id="no_telp" value="<?= htmlspecialchars($karyawanData['no_telp']) ?>"
                class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan nomor telepon" />
        </div>

        <div>
            <label for="status_kerja" class="block text-sm font-medium text-gray-700">Status Kerja</label>
            <select name="status_kerja" id="status_kerja" class="border rounded px-3 py-2 text-sm w-full" required>
                <option value="aktif" <?= $karyawanData['status_kerja'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="tidak_aktif" <?= $karyawanData['status_kerja'] == 'tidak_aktif' ? 'selected' : '' ?>>
                    Tidak Aktif</option>
            </select>
        </div>

        <!-- Form Pengguna -->
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($penggunaData['username']) ?>"
                class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan username pengguna" required />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password (Kosongkan jika tidak
                diubah)</label>
            <input type="password" name="password" id="password" class="border rounded px-3 py-2 text-sm w-full"
                placeholder="Masukkan password pengguna" />
        </div>

        <div>
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Perbarui</button>
            <a href="<?= $link ?>"
                class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 text-sm">Kembali</a>
        </div>
    </form>
</main>

<?php }elseif ($req2 === 'hapus' && is_numeric($req3)) {
    // Ambil karyawan_id dari req3
    $karyawan_id = $req3;

    try {
        // Mulai transaksi
        $db->pdo->beginTransaction();

        // Hapus data dari Pengguna
        $hapusPengguna = $db->deleteData('Pengguna', 'karyawan_id = ?', [$karyawan_id]);

        // Hapus data dari Karyawan
        $hapusKaryawan = $db->deleteData('Karyawan', 'karyawan_id = ?', [$karyawan_id]);

        // Cek apakah kedua query berhasil
        if ($hapusPengguna && $hapusKaryawan) {
            // Commit jika semua berhasil
            $db->pdo->commit();
            echo $db->alert('Data karyawan dan pengguna berhasil dihapus.', $link);
        } else {
            // Rollback jika ada yang gagal
            $db->pdo->rollBack();
            echo $db->alert('Gagal menghapus data karyawan dan pengguna.', $link);
        }
    } catch (PDOException $e) {
        // Rollback jika ada error
        $db->pdo->rollBack();
        echo $db->alert('Terjadi kesalahan: ' . $e->getMessage(), $link);
    }
    exit;
}else {
    echo $db->alert('Halaman tidak ditemukan.', $link);
}