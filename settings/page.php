<?php

if (!isset($_SESSION['pengguna_id'])) {
    header('Location: login.php');
    exit;
}

// mengatur akses
$role = $_SESSION['role'] ?? null;
$req1 = $req1 ?? 'home';

// Daftar halaman yang boleh diakses sesuai role
$akses = [
    'Admin' => ['','home', 'dashboard', 'unit', 'admin', 'supervisor', 'karyawan', 'logbook', 'laporan-kinerja'],
    'Supervisor' => ['','home', 'dashboard', 'logbook-supervisor'],
    'Karyawan' => ['', 'dashboard', 'logbook-karyawan']
];

// Jika role tidak dikenali atau akses ditolak
if (!isset($akses[$role]) || !in_array($req1, $akses[$role])) {
    $req1 = "404";
}

if ($req1) {
    switch ($req1) {
        case 'home':
            $title = "home - Logbook Admin";
            $includePage = "pages/home.php"; // File untuk unit
            break;
        case 'home-karyawan':
            $title = "home-karyawan";
            $includePage = "pages/home_karyawan.php"; // File untuk unit
            break;
        case 'dashboard':
            $title = "dashboard - Logbook Admin";
            $includePage = "pages/dashboard.php"; // File untuk kelas
            break;
        case 'unit':
            $title = "unit - Logbook Admin";
            $includePage = "pages/unit.php";
            break;
        case 'admin':
            $title = "admin - Logbook Admin";
            $includePage = "pages/admin.php";
            break;
        case 'supervisor':
            $title = "supervisor - Logbook Admin";
            $includePage = "pages/supervisor.php";
            break;
        case 'karyawan':
            $title = "karyawan - Logbook Admin";
            $includePage = "pages/karyawan.php";
            break;
        case 'logbook':
            $title = "Logbook";
            $includePage = "pages/logbook-admin.php";
            break;
        case 'logbook-karyawan':
            $title = "Logbook karyawan";
            $includePage = "pages/logbook-karyawan.php";
            break;
        case 'logbook-supervisor':
            $title = "Logbook supervisor";
            $includePage = "pages/logbook-supervisor.php";
            break;
        case 'laporan-kinerja':
            $title = "Logbook laporan kinerja";
            $includePage = "pages/laporan-kinerja.php";
            break;
        case '404':
            $title = "404 - Tidak ditemukan";
            $includePage = "pages/default.php";
            break;
        default:
            $title = ucfirst($req1) . " - Logbook Admin"; // Dinamis berdasarkan req1
            $includePage = "pages/default.php"; // File default jika tidak ada kecocokan
            break;
    }
} else {
    if ($role == "Admin") {
        $title = "Home - Logbook Admin";
        $includePage = "pages/home.php";
    } elseif ($role == "Karyawan") {
        $title = "Home - Logbook Karyawan";
        $includePage = "pages/home_karyawan.php";
    } elseif ($role == "Supervisor") {
        $title = "Home - Logbook Supervisor";
        $includePage = "pages/home.php";
    }
}