<?php
// Jumlah karyawan
$jumlah_karyawan = $db->lihatData("Karyawan", "COUNT(*)", "1");

// Jumlah logbook tertunda
$jumlah_logbook_tunda = $db->lihatData("Logbook", "COUNT(*)", "status IS NULL OR status = 'tunda'");

// Jumlah logbook selesai
$jumlah_logbook_selesai = $db->lihatData("Logbook", "COUNT(*)", "status = 'selesai'");

// Ambil data setting berdasarkan ket
$data_setting = $db->lihatdata("Setting", "isian", "keterangan = ?", ['link_excel_kategori']);

// Update Link Excel
if (isset($_POST['update'])) {
    $isian = $_POST['isian'];
    $ket = $_POST['ket'];

    $update = $db->updateData("Setting", ["isian"], "keterangan = ?", [$isian, $ket]);

    if ($update) {
        echo $db->alert("Data berhasil diperbarui!", $link_web); // ganti ke halaman tujuanmu
    } else {
        echo $db->alert("Gagal memperbarui data.", $link_web);
    }
}

?>

<main class="p-6">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Selamat Datang di Sistem Logbook Kompetensi Manajerial Rumah Sakit
        </h1>
        <p class="text-gray-600 mt-2">Pantau dan kelola aktivitas harian dengan efisien dan terorganisir.</p>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Jumlah Karyawan</p>
                    <h2 class="text-2xl font-bold text-blue-700"><?= $jumlah_karyawan ?></h2>
                </div>
                <i class="fas fa-user-md text-blue-500 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Logbook Tertunda</p>
                    <h2 class="text-2xl font-bold text-yellow-600"><?= $jumlah_logbook_tunda ?></h2>
                </div>
                <i class="fas fa-clock text-yellow-500 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Logbook Selesai</p>
                    <h2 class="text-2xl font-bold text-green-600"><?= $jumlah_logbook_selesai ?></h2>
                </div>
                <i class="fas fa-check-circle text-green-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Menu Navigasi -->
    <!-- Menu Navigasi -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-blue-700">Unit</h2>
                <i class="fas fa-hospital-alt text-blue-500 text-xl"></i>
            </div>
            <p class="text-gray-600 text-sm">Kelola daftar unit rumah sakit beserta aktivitas dan laporan masing-masing.
            </p>
            <a href="unit" class="mt-4 inline-block text-blue-600 hover:underline text-sm">Lihat Unit</a>
        </div>

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-green-700">Karyawan</h2>
                <i class="fas fa-user-nurse text-green-500 text-xl"></i>
            </div>
            <p class="text-gray-600 text-sm">Data dan aktivitas karyawan dapat diakses dan diperbarui secara berkala.
            </p>
            <a href="karyawan" class="mt-4 inline-block text-green-600 hover:underline text-sm">Lihat Karyawan</a>
        </div>

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-purple-700">Laporan</h2>
                <i class="fas fa-file-medical text-purple-500 text-xl"></i>
            </div>
            <p class="text-gray-600 text-sm">Akses laporan kinerja dan aktivitas logbook karyawan dan supervisor.</p>
            <a href="laporan-kinerja" class="mt-4 inline-block text-purple-600 hover:underline text-sm">Lihat
                Laporan</a>
        </div>
    </div>

    <!-- Form Edit Link Excel -->
    <div class="bg-white rounded-xl shadow p-6 max-w-xl mx-auto">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Edit Link Excel</h2>
        <form action="" method="post">
            <div class="mb-4">
                <label for="isian" class="block text-sm font-medium text-gray-700 mb-1">Link Excel</label>
                <input type="text" id="isian" name="isian" value="<?= htmlspecialchars($data_setting) ?>"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Masukkan link Google Sheet...">
            </div>
            <input type="hidden" name="ket" value="link_excel_kategori">
            <button type="submit" name="update"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                Simpan
            </button>
        </form>
    </div>



</main>