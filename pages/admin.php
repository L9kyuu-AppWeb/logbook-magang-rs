<?php
// Membuat link untuk halaman ini
$link = "$link_web/$req1";

// Cek apakah ada nilai req2 di URL
if (empty($req2)) {
    // Jika req2 kosong, tampilkan daftar admin

    // Ambil nilai pencarian dari session
    $cari = $_SESSION['cari_admin'] ?? '';

    // Update session jika ada pencarian baru
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cari'])) {
        $_SESSION['cari_admin'] = $_POST['cari'];
        $cari = $_POST['cari'];
        echo $db->alert2($link);
    } 

    // Menghapus nilai pencarian
    if ($req3 == "reset") {
        unset($_SESSION['cari_admin']);
        $cari = '';
        echo $db->alert2($link);
    }

    $dataAdmin = $db->joinTable(
        'a.admin_id, a.nama, a.email, p.username, p.role',
        'Admin a',
        'Pengguna p',
        'a.admin_id = p.admin_id',
        ($cari != '' ? '(a.nama LIKE ? OR a.email LIKE ? OR p.username LIKE ?)' : ''),
        ($cari != '' ? ["%$cari%", "%$cari%", "%$cari%"] : [])
    );

    // Tampilkan tabel admin
    ?>
    <main class="p-6">
        <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Admin</h2>
                <a href="<?= $link ?>/tambah" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">
                    + Tambah Admin
                </a>
            </div>
            <form method="POST" class="flex gap-2">
                <input type="text" name="cari" value="<?= htmlspecialchars($cari) ?>" placeholder="Cari nama atau email admin..." class="border rounded px-3 py-1 text-sm" />
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">Cari</button>
                <a href="<?= "$link//reset" ?>" class="bg-gray-400 text-white px-3 py-1 rounded hover:bg-gray-500 text-sm">Reset</a>
            </form>
        </div>

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Admin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (count($dataAdmin) === 0): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data admin.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dataAdmin as $row): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($row['admin_id']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($row['nama']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($row['email']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($row['username']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($row['role']) ?></td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="<?= "$link/ubah/$row[admin_id]" ?>" class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500 text-xs">Edit</a>
                                    <a href="<?= "$link/hapus/$row[admin_id]" ?>" onclick="return confirm('Yakin ingin menghapus admin ini?')" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 text-xs">Hapus</a>
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
    // Jika req2 adalah "tambah", tampilkan form tambah admin

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama = trim($_POST['nama']);
        $email = trim($_POST['email']);
        $username = trim($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = 'admin'; // dikunci ke admin

        try {
            // Mulai transaksi manual lewat $db->pdo
            $db->pdo->beginTransaction();

            // Insert ke tabel Admin
            $simpanAdmin = $db->insertData('Admin', ['nama', 'email'], [$nama, $email]);
            if (!$simpanAdmin) {
                throw new Exception("Gagal menyimpan data Admin.");
            }

            // Ambil admin_id terakhir
            $admin_id = $db->lastInsertId();

            // Insert ke tabel Pengguna
            $simpanPengguna = $db->insertData('Pengguna', ['username', 'password', 'role', 'admin_id'], [$username, $password, $role, $admin_id]);
            if (!$simpanPengguna) {
                throw new Exception("Gagal menyimpan data Pengguna.");
            }

            // Commit transaksi
            $db->pdo->commit();

            echo $db->alert('Admin berhasil ditambahkan!', $link);
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
            <h2 class="text-2xl font-bold text-gray-800">Tambah Admin</h2>
        </div>

        <form action="<?= $link ?>/tambah" method="POST" class="space-y-4">
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="nama" id="nama" class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan nama admin" required />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan email admin" required />
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan username admin" required />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan password" required />
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="role" class="border rounded px-3 py-2 text-sm w-full bg-gray-100 text-gray-600" readonly disabled>
                    <option value="admin" selected>Admin</option>
                </select>
                <!-- Hidden field to actually submit the value -->
                <input type="hidden" name="role" value="admin" />
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Simpan</button>
                <a href="<?= $link ?>" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 text-sm">Kembali</a>
            </div>
        </form>

    </main>
    <?php
} elseif ($req2 === 'ubah' && is_numeric($req3)) {
    // Ambil admin_id dari req3
    $admin_id = $req3;

    // Ambil data admin berdasarkan admin_id
    $adminData = $db->tampilData('Admin', [
        'kolom' => 'admin_id, nama, email',
        'where' => 'admin_id = ?',
        'params' => [$admin_id]
    ]);

    if (count($adminData) === 0) {
        echo $db->alert('Admin tidak ditemukan.', $link);
        exit;
    }

    $adminData = $adminData[0];

    // Ambil data pengguna terkait admin_id
    $penggunaData = $db->tampilData('Pengguna', [
        'kolom' => 'pengguna_id, username, password, role, karyawan_id, supervisor_id',
        'where' => 'admin_id = ?',
        'params' => [$admin_id]
    ]);

    if (count($penggunaData) === 0) {
        echo $db->alert('Pengguna terkait tidak ditemukan.', $link);
        exit;
    }

    $penggunaData = $penggunaData[0];

    // Proses update data jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ambil data dari form dan sanitasi
        $nama = trim($_POST['nama']);
        $email = trim($_POST['email']);
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);  // Password baru (bisa kosong)
        $role = $_POST['role'];

        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo $db->alert('Format email tidak valid.', $link);
            exit;
        }

        // Validasi username dan role
        if (empty($nama) || empty($email) || empty($username) || empty($role)) {
            echo $db->alert('Nama, Email, Username dan Role tidak boleh kosong.', $link);
            exit;
        }

        // Update data admin
        $updateAdmin = $db->updateData('Admin', ['nama', 'email'], 'admin_id = ?', [$nama, $email, $admin_id]);

        // Cek apakah password diisi atau tidak
        if (!empty($password)) {
            // Jika password diisi, update password juga
            $updatePengguna = $db->updateData('Pengguna', ['username', 'password', 'role'], 'admin_id = ?', [$username, password_hash($password, PASSWORD_DEFAULT), $role, $admin_id]);
        } else {
            // Jika password kosong, hanya update username dan role
            $updatePengguna = $db->updateData('Pengguna', ['username', 'role'], 'admin_id = ?', [$username, $role, $admin_id]);
        }

        if ($updateAdmin && $updatePengguna) {
            echo $db->alert('Admin dan pengguna berhasil diperbarui!', $link);
        } else {
            echo $db->alert('Gagal memperbarui data.', $link);
        }
    }

    // Tampilkan form edit admin dan pengguna
    ?>
    <main class="p-6">
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Edit Admin dan Pengguna</h2>
        </div>

        <form action="<?= $link ?>/ubah/<?= $admin_id ?>" method="POST" class="space-y-4">
            <!-- Form Admin -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($adminData['nama']) ?>" class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan nama admin" required />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($adminData['email']) ?>" class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan email admin" required />
            </div>

            <!-- Form Pengguna -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($penggunaData['username']) ?>" class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan username pengguna" required />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" id="password" class="border rounded px-3 py-2 text-sm w-full" placeholder="Masukkan password pengguna" />
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="role" class="border rounded px-3 py-2 text-sm w-full" required>
                    <option value="admin" <?= $penggunaData['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="supervisor" <?= $penggunaData['role'] === 'supervisor' ? 'selected' : '' ?>>Supervisor</option>
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
    $adminId = $req3;

    try {
        // Mulai transaksi
        $db->pdo->beginTransaction();

        // Hapus pengguna terkait
        $hapusPengguna = $db->deleteData('Pengguna', 'admin_id = ?', [$adminId]);

        // Hapus admin
        $hapusAdmin = $db->deleteData('Admin', 'admin_id = ?', [$adminId]);

        // Pastikan kedua operasi berhasil
        if ($hapusPengguna && $hapusAdmin) {
            // Commit jika semua berhasil
            $db->pdo->commit();
            echo $db->alert("Admin dan pengguna berhasil dihapus.", $link);
        } else {
            // Rollback jika ada yang gagal
            $db->pdo->rollBack();
            echo $db->alert("Gagal menghapus admin dan pengguna.", $link);
        }

    } catch (PDOException $e) {
        // Rollback transaksi jika ada error
        $db->pdo->rollBack();
        echo $db->alert("Gagal menghapus admin: " . $e->getMessage(), $link);
    }
    exit;
}else {
    echo $db->alert('Halaman tidak ditemukan.', $link);
}
