<header class="bg-white shadow-sm flex items-center justify-between px-6 py-4 sticky top-0 z-20">
    <div class="flex items-center">
        <button id="menu-button" class="text-gray-700 md:hidden focus:outline-none mr-4">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>
    <div class="flex items-center space-x-4">
        <a href="logout.php" onclick="return confirm('Yakin ingin logout?')"
            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition inline-flex items-center">
            <i class="fas fa-sign-out-alt mr-2"></i>Logout
        </a>
    </div>
</header>