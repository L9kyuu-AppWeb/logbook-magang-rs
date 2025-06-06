<aside id="sidebar"
    class="bg-blue-800 w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition-all duration-300 z-30 shadow-xl">
    <div class="px-4 mb-6">
        <div class="flex items-center space-x-2">
            <i class="fas fa-book-open text-white text-2xl"></i>
            <h2 class="text-white font-bold text-xl">LogBook</h2>
        </div>
    </div>

    <div class="px-4 mb-6">
        <div class="flex items-center space-x-3 bg-blue-900 rounded-lg p-3">
            <div class="bg-blue-700 rounded-full p-2">
                <i class="fas fa-user text-white"></i>
            </div>
            <div>
                <h3 class="text-white font-medium text-sm"><?= $_SESSION['nama'] ?></h3>
                <p class="text-blue-300 text-xs">
                    <?php
                $unit = $_SESSION['unit'];
                $role = $_SESSION['role'] ?? '';

                if ($role === 'Supervisor') {
                    echo "$unit - Unit";
                } elseif ($role === 'Karyawan') {
                    echo "$unit - Pembimbing";
                } else {
                    echo $unit; // administrator atau lainnya
                }
                ?>
                </p>
            </div>
        </div>
    </div>


    <!-- hover include -->
    <?php include 'settings/hover.php' ?>
    <nav class="px-4 space-y-1">
        <?php foreach ($menus as $menu): ?>
        <a href="<?= $menu['url'] ?>" class="flex items-center py-3 px-4 rounded-lg transition duration-200
              <?= ($req1 === $menu['path'] || ($req1 === '' && $menu['path'] === '')) 
                  ? 'bg-blue-700 text-white' 
                  : 'text-blue-200 hover:text-white hover:bg-blue-700' ?>">
            <i class="fas <?= $menu['icon'] ?> mr-3"></i>
            <span><?= $menu['label'] ?></span>
        </a>
        <?php endforeach; ?>
    </nav>

    <!-- <div class="px-4 mt-12">
        <div class="bg-blue-900 rounded-lg p-4">
            <h4 class="text-white text-sm font-medium mb-2">Kegiatan Hari Ini</h4>
            <div class="text-blue-200 text-xs">
                <p class="mb-1">6 tugas perlu diselesaikan</p>
                <div class="w-full bg-blue-700 rounded-full h-2">
                    <div class="bg-green-400 h-2 rounded-full" style="width: 65%"></div>
                </div>
            </div>
        </div>
    </div> -->
</aside>