<main class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard <?= $req1 ?></h2>
        <p class="text-gray-600">Kelola dan pantau semua aktivitas catatan harian Anda</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 stat-card transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Entri</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">248</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-book text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-green-500 text-sm flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i> 12%
                </span>
                <span class="text-gray-400 text-sm ml-2">dari bulan lalu</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 stat-card transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Entri Minggu Ini</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">24</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-calendar-week text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-green-500 text-sm flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i> 8%
                </span>
                <span class="text-gray-400 text-sm ml-2">dari minggu lalu</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 stat-card transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Tugas Tertunda</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">12</h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <i class="fas fa-tasks text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-red-500 text-sm flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i> 5%
                </span>
                <span class="text-gray-400 text-sm ml-2">dari minggu lalu</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 stat-card transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pengguna Aktif</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">16</h3>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <span class="text-green-500 text-sm flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i> 22%
                </span>
                <span class="text-gray-400 text-sm ml-2">dari bulan lalu</span>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Quick Add -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-semibold text-lg text-gray-800">Aktivitas Terbaru</h3>
                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua</button>
            </div>

            <div class="space-y-4">
                <div class="flex items-start space-x-4 activity-item bg-gray-50 p-4 rounded-lg transition-all">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-file-alt text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <h4 class="font-medium text-gray-800">Laporan Kegiatan Bulanan</h4>
                            <span class="text-gray-400 text-sm">2 jam yang lalu</span>
                        </div>
                        <p class="text-gray-600 text-sm mt-1">Admin menambahkan laporan baru untuk bulan Mei
                        </p>
                    </div>
                </div>

                <div class="flex items-start space-x-4 activity-item bg-gray-50 p-4 rounded-lg transition-all">
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <h4 class="font-medium text-gray-800">Tugas Selesai</h4>
                            <span class="text-gray-400 text-sm">4 jam yang lalu</span>
                        </div>
                        <p class="text-gray-600 text-sm mt-1">User1 menyelesaikan tugas "Pembuatan Laporan
                            Keuangan"</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4 activity-item bg-gray-50 p-4 rounded-lg transition-all">
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-user-plus text-purple-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <h4 class="font-medium text-gray-800">Pengguna Baru</h4>
                            <span class="text-gray-400 text-sm">Kemarin</span>
                        </div>
                        <p class="text-gray-600 text-sm mt-1">Admin menambahkan user baru "staff_marketing"
                        </p>
                    </div>
                </div>

                <div class="flex items-start space-x-4 activity-item bg-gray-50 p-4 rounded-lg transition-all">
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <h4 class="font-medium text-gray-800">Pengingat Deadline</h4>
                            <span class="text-gray-400 text-sm">2 hari yang lalu</span>
                        </div>
                        <p class="text-gray-600 text-sm mt-1">3 tugas akan mencapai deadline dalam 48 jam
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-6">Tambah Entri Cepat</h3>

            <form>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="title">
                        Judul
                    </label>
                    <input type="text" id="title"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Judul entri logbook">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="category">
                        Kategori
                    </label>
                    <select id="category"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>Pilih Kategori</option>
                        <option>Tugas Harian</option>
                        <option>Meeting</option>
                        <option>Proyek</option>
                        <option>Lainnya</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="description">
                        Deskripsi
                    </label>
                    <textarea id="description" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Deskripsi singkat aktivitas..."></textarea>
                </div>

                <div class="flex space-x-4 mb-4">
                    <div class="w-1/2">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="date">
                            Tanggal
                        </label>
                        <input type="date" id="date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="priority">
                            Prioritas
                        </label>
                        <select id="priority"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>Normal</option>
                            <option>Rendah</option>
                            <option>Tinggi</option>
                            <option>Urgen</option>
                        </select>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Entri
                </button>
            </form>

            <div class="border-t border-gray-200 mt-6 pt-6">
                <h4 class="font-medium text-gray-700 mb-4">Shortcut</h4>
                <div class="grid grid-cols-2 gap-3">
                    <button
                        class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 p-3 rounded-lg transition">
                        <i class="fas fa-clipboard-list text-gray-600 mr-2"></i>
                        <span class="text-sm">Tugas</span>
                    </button>
                    <button
                        class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 p-3 rounded-lg transition">
                        <i class="fas fa-users text-gray-600 mr-2"></i>
                        <span class="text-sm">Meeting</span>
                    </button>
                    <button
                        class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 p-3 rounded-lg transition">
                        <i class="fas fa-chart-line text-gray-600 mr-2"></i>
                        <span class="text-sm">Laporan</span>
                    </button>
                    <button
                        class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 p-3 rounded-lg transition">
                        <i class="fas fa-cog text-gray-600 mr-2"></i>
                        <span class="text-sm">Pengaturan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>