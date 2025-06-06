<?php
// Cek apakah sesi sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>
<?php include 'settings/env.php' ?>
<!-- koneksi include -->
<?php include 'databases/koneksi.php' ?>
<!-- route include -->
<?php include 'settings/route.php' ?>
<!-- include pengaturan pages -->
<?php include 'settings/page.php' ?>
<!-- header include -->
<?php include 'includes/head.php' ?>

<body class="bg-gray-50">
    <!-- Wrapper -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <!-- Menu include -->
        <?php include 'includes/menu.php' ?>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <!-- Header Include -->
            <?php include 'includes/header.php' ?>

            <!-- Content -->
            <?php
                if (file_exists($includePage)) {
                    include($includePage); // Meng-include file berdasarkan $includePage
                } else {
                    echo "<p>Halaman tidak ditemukan.</p>";
                }
            ?>
        </div>
    </div>

    <!-- Toggle script -->
    <script>
    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.getElementById('menu-button');
    menuBtn.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
    });
    </script>
</body>
</html>