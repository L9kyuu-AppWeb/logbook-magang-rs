<?php
// Tentukan menu berdasarkan role
$menus = [];

switch ($role) {
    case 'Admin':
        $menus = [
            ['label' => 'Home', 'icon' => 'fa-home', 'path' => '', 'url' => $link_web],
            ['label' => 'Unit', 'icon' => 'fa-building', 'path' => 'unit', 'url' => "$link_web/unit"],
            ['label' => 'Admin', 'icon' => 'fa-user-shield', 'path' => 'admin', 'url' => "$link_web/admin"],
            ['label' => 'Supervisor', 'icon' => 'fa-user-tie', 'path' => 'supervisor', 'url' => "$link_web/supervisor"],
            ['label' => 'Karyawan', 'icon' => 'fa-users', 'path' => 'karyawan', 'url' => "$link_web/karyawan"],
            ['label' => 'LogBook', 'icon' => 'fa-book', 'path' => 'logbook', 'url' => "$link_web/logbook"],
            ['label' => 'Laporan Kinerja', 'icon' => 'fa-file-alt', 'path' => 'laporan-kinerja', 'url' => "$link_web/laporan-kinerja"],
        ];
        break;

    case 'Karyawan':
        $menus = [
            ['label' => 'Home', 'icon' => 'fa-home', 'path' => '', 'url' => "$link_web"],
            ['label' => 'LogBook Karyawan', 'icon' => 'fa-book', 'path' => 'logbook-karyawan', 'url' => "$link_web/logbook-karyawan"],
        ];
        break;

    case 'Supervisor':
        $menus = [
            ['label' => 'Home', 'icon' => 'fa-home', 'path' => '', 'url' => $link_web],
            ['label' => 'LogBook Supervisor', 'icon' => 'fa-book', 'path' => 'logbook-supervisor', 'url' => "$link_web/logbook-supervisor"],
        ];
        break;
}
?>
