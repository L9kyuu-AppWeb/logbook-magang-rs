<?php
// Membuat link untuk halaman ini
$link = "$link_web/$req1";
// Menampilkan daftar supervisor
if (empty($req2)) {
    // Jika req2 kosong, tampilkan daftar supervisor

    // Ambil nilai pencarian dari session
    $cari = $_SESSION['cari_supervisor'] ?? '';

    // Update session jika ada pencarian baru
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cari'])) {
        $_SESSION['cari_supervisor'] = $_POST['cari'];
        $cari = $_POST['cari'];
        echo $db->alert2($link);
    }

    // Menghapus nilai pencarian
    if ($req3 == "reset") {
        unset($_SESSION['cari_supervisor']);
        $cari = '';
        echo $db->alert2($link);
    }

    // Ambil data supervisor dan unit terkait dengan pencarian
    $sql = "
        SELECT 
            s.supervisor_id, 
            s.nama, 
            s.unit_id, 
            u.nama_unit, 
            p.username 
        FROM Supervisor s
        JOIN Unit u ON s.unit_id = u.unit_id
        JOIN Pengguna p ON s.supervisor_id = p.supervisor_id
    ";

    // Jika ada pencarian, tambahkan WHERE
    if ($cari != '') {
        $sql .= " WHERE s.nama LIKE ? OR u.nama_unit LIKE ? OR p.username LIKE ?";
        $params = ["%$cari%", "%$cari%", "%$cari%"];
    } else {
        $params = [];
    }

    $dataSupervisor = $db->queryBebas($sql, $params);
   
    
    
    // Tampilkan tabel supervisor
    ?>
    <main class="p-6">
        <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Supervisor</h2>
                <a href="<?= $link ?>/tambah" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">
                    + Tambah Supervisor
                </a>
            </div>
            <form method="POST" class="flex gap-2">
                <input type="text" name="cari" value="<?= htmlspecialchars($cari) ?>" placeholder="Cari nama username atau unit..." class="border rounded px-3 py-1 text-sm" />
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">Cari</button>
                <a href="<?= "$link//reset" ?>" class="bg-gray-400 text-white px-3 py-1 rounded hover:bg-gray-500 text-sm">Reset</a>
            </form>
        </div>

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Supervisor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (count($dataSupervisor) === 0): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data supervisor.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dataSupervisor as $row): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($row['supervisor_id']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($row['nama']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($row['nama_unit']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($row['username']) ?></td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="<?= "$link/ubah/$row[supervisor_id]" ?>" class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500 text-xs">Edit</a>
                                    <a href="<?= "$link/hapus/$row[supervisor_id]" ?>" onclick="return confirm('Yakin ingin menghapus supervisor ini?')" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 text-xs">Hapus</a>
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
    // Jika req2 adalah "tambah", tampilkan form tambah supervisor

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama = trim($_POST['nama']);
        $username = trim($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $unit_id = $_POST['unit_id'];
        $role = 'supervisor'; // Role dikunci ke supervisor

        try {
            // Mulai transaksi manual lewat $db->pdo
            $db->pdo->beginTransaction();

            // Insert ke tabel Supervisor
            $simpanSupervisor = $db->insertData('Supervisor', ['nama', 'unit_id'], [$nama, $unit_id]);
            if (!$simpanSupervisor) {
                throw new Exception("Gagal menyimpan data Supervisor.");
            }

            // Ambil supervisor_id terakhir
            $supervisor_id = $db->lastInsertId();

            // Insert ke tabel Pengguna
            $simpanPengguna = $db->insertData('Pengguna', ['username', 'password', 'role', 'supervisor_id'], [$username, $password, $role, $supervisor_id]);
            if (!$simpanPengguna) {
                throw new Exception("Gagal menyimpan data Pengguna.");
            }

            // Commit transaksi
            $db->pdo->commit();

            echo $db->alert('Supervisor berhasil ditambahkan!', $link);
        } catch (Exception $e) {
            // Rollback jika ada error
            $db->pdo->rollBack();
            echo $db->alert('Terjadi kesalahan: ' . $e->getMessage(), $link);
        }
        exit;
    }
    ?>
    <main class="p-6">
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Tambah Supervisor</h2>
        </div>

        <form action="<?= $link ?>/tambah" method="POST" class="space-y-4">
            <!-- Nama Supervisor -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Supervisor</label>
                <input type="text" name="nama" id="nama" class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan nama supervisor" required />
            </div>

            <!-- Username Pengguna -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan username supervisor" required />
            </div>

            <!-- Password Pengguna -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan password" required />
            </div>

            <!-- Unit Supervisor -->
            <div>
                <label for="unit_id" class="block text-sm font-medium text-gray-700">Unit</label>
                <select name="unit_id" id="unit_id" class="border rounded px-3 py-2 text-sm w-full" required>
                    <?php
                    // Ambil data unit untuk pilihan
                    $units = $db->tampilData('Unit', ['kolom' => 'unit_id, nama_unit']);
                    foreach ($units as $unit):
                    ?>
                        <option value="<?= $unit['unit_id'] ?>"><?= htmlspecialchars($unit['nama_unit']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Role (Dikunci ke Supervisor) -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="role" class="border rounded px-3 py-2 text-sm w-full bg-gray-100 text-gray-600" readonly disabled>
                    <option value="supervisor" selected>Supervisor</option>
                </select>
                <!-- Hidden field to actually submit the value -->
                <input type="hidden" name="role" value="supervisor" />
            </div>

            <!-- Tombol Simpan dan Kembali -->
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Simpan</button>
                <a href="<?= $link ?>" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 text-sm">Kembali</a>
            </div>
        </form>

    </main>
<?php
} elseif ($req2 === 'ubah' && is_numeric($req3)) {
    $supervisor_id = $req3;

    // Ambil data Supervisor
    $supervisorData = $db->tampilData('Supervisor', [
        'kolom' => 'supervisor_id, nama, unit_id',
        'where' => 'supervisor_id = ?',
        'params' => [$supervisor_id]
    ]);

    if (count($supervisorData) === 0) {
        echo $db->alert('Supervisor tidak ditemukan.', $link);
        exit;
    }

    $supervisorData = $supervisorData[0];

    // Ambil data pengguna
    $penggunaData = $db->tampilData('Pengguna', [
        'kolom' => 'pengguna_id, username, password, role, supervisor_id',
        'where' => 'supervisor_id = ?',
        'params' => [$supervisor_id]
    ]);

    if (count($penggunaData) === 0) {
        echo $db->alert('Pengguna terkait tidak ditemukan.', $link);
        exit;
    }

    $penggunaData = $penggunaData[0];

    // Jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama = trim($_POST['nama']);
        $unit_id = $_POST['unit_id'];
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $role = $_POST['role'];

        if (empty($nama) || empty($username) || empty($role) || empty($unit_id)) {
            echo $db->alert('Semua field wajib diisi.', $link);
            exit;
        }

        // Update Supervisor
        $updateSupervisor = $db->updateData('Supervisor', ['nama', 'unit_id'], 'supervisor_id = ?', [$nama, $unit_id, $supervisor_id]);

        // Update Pengguna
        if (!empty($password)) {
            $updatePengguna = $db->updateData('Pengguna', ['username', 'password', 'role'], 'supervisor_id = ?', [$username, password_hash($password, PASSWORD_DEFAULT), $role, $supervisor_id]);
        } else {
            $updatePengguna = $db->updateData('Pengguna', ['username', 'role'], 'supervisor_id = ?', [$username, $role, $supervisor_id]);
        }

        if ($updateSupervisor && $updatePengguna) {
            echo $db->alert('Supervisor dan pengguna berhasil diperbarui!', $link);
        } else {
            echo $db->alert('Gagal memperbarui data.', $link);
        }
    }

    // Ambil semua unit untuk dropdown
    $unitList = $db->tampilData('Unit', ['kolom' => 'unit_id, nama_unit']);
    ?>

    <main class="p-6">
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Edit Supervisor dan Pengguna</h2>
        </div>

        <form action="<?= $link ?>/ubah/<?= $supervisor_id ?>" method="POST" class="space-y-4">
            <!-- Form Supervisor -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Supervisor</label>
                <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($supervisorData['nama']) ?>" class="border rounded px-3 py-2 text-sm w-full" required />
            </div>

            <div>
                <label for="unit_id" class="block text-sm font-medium text-gray-700">Unit</label>
                <select name="unit_id" id="unit_id" class="border rounded px-3 py-2 text-sm w-full" required>
                    <option value="">-- Pilih Unit --</option>
                    <?php foreach ($unitList as $unit): ?>
                        <option value="<?= $unit['unit_id'] ?>" <?= $unit['unit_id'] == $supervisorData['unit_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($unit['nama_unit']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Form Pengguna -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($penggunaData['username']) ?>" class="border rounded px-3 py-2 text-sm w-full" required />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" id="password" class="border rounded px-3 py-2 text-sm w-full" />
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="role" class="border rounded px-3 py-2 text-sm w-full" required>
                    <option value="supervisor" <?= $penggunaData['role'] === 'supervisor' ? 'selected' : '' ?>>Supervisor</option>
                    <option value="admin" <?= $penggunaData['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="karyawan" <?= $penggunaData['role'] === 'karyawan' ? 'selected' : '' ?>>Karyawan</option>
                </select>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Perbarui</button>
                <a href="<?= $link ?>" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 text-sm">Kembali</a>
            </div>
        </form>
    </main>

<?php
} elseif ($req2 == 'hapus' && is_numeric($req3)) {
    $supervisorId = $req3;

    try {
        // Mulai transaksi
        $db->pdo->beginTransaction();

        // Hapus pengguna yang terkait dengan supervisor_id ini
        $hapusPengguna = $db->deleteData('Pengguna', 'supervisor_id = ?', [$supervisorId]);

        // Hapus supervisor
        $hapusSupervisor = $db->deleteData('Supervisor', 'supervisor_id = ?', [$supervisorId]);

        // Cek apakah kedua operasi berhasil
        if ($hapusPengguna && $hapusSupervisor) {
            $db->pdo->commit();
            echo $db->alert("Supervisor dan pengguna berhasil dihapus.", $link);
        } else {
            $db->pdo->rollBack();
            echo $db->alert("Gagal menghapus supervisor dan pengguna.", $link);
        }

    } catch (PDOException $e) {
        $db->pdo->rollBack();
        echo $db->alert("Gagal menghapus supervisor: " . $e->getMessage(), $link);
    }
    exit;
}else {
    echo $db->alert('Halaman tidak ditemukan.', $link);
}

?>
