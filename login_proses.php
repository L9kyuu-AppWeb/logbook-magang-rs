<?php
session_start();
header('Content-Type: application/json');
require 'databases/koneksi.php'; // atau sesuaikan
require 'settings/route.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $captcha_input = $_POST['captcha'];
    $captcha_kode = $_POST['kode_captcha'];

if ($captcha_input != $captcha_kode) {
    error_log("Captcha Input: " . $captcha_input);
    error_log("Captcha Session: " . $captcha_kode);
    echo json_encode(['success' => false, 'message' => 'Captcha salah']);
    exit;
}

    // Ambil data pengguna
    $pengguna = $db->tampilData('Pengguna', [
        'where' => 'username = ?',
        'params' => [$username]
    ]);

    if (!empty($pengguna) && password_verify($password, $pengguna[0]['password'])) {
        $user = $pengguna[0];
        $_SESSION['pengguna_id'] = $user['pengguna_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Role-based redirect
        if ($user['role'] === 'Karyawan') {
            $_SESSION['karyawan_id'] = $user['karyawan_id'];
            $_SESSION['nama'] = $db->lihatdata('Karyawan','nama','karyawan_id = ?',[$_SESSION['karyawan_id']]);
            $_SESSION['unit_id'] = $db->lihatdata('Karyawan','supervisor_id','karyawan_id = ?',[$_SESSION['karyawan_id']]);
            $_SESSION['unit'] = $db->lihatdata('Supervisor','nama','supervisor_id = ?',[$_SESSION['unit_id']]);
        } elseif ($user['role'] === 'Supervisor') {
            $_SESSION['supervisor_id'] = $user['supervisor_id'];
            $_SESSION['nama'] = $db->lihatdata('Supervisor', 'nama', 'supervisor_id = ?', [$_SESSION['supervisor_id']]);
            $_SESSION['unit_id'] = $db->lihatdata('Supervisor', 'unit_id', 'supervisor_id = ?', [$_SESSION['supervisor_id']]);
            $_SESSION['unit'] = $db->lihatdata('Unit', 'nama_unit', 'unit_id = ?', [$_SESSION['unit_id']]);
        } elseif ($user['role'] === 'Admin') {
            $_SESSION['admin_id'] = $user['admin_id'];
            $_SESSION['nama'] = $db->lihatdata('Admin', 'nama', 'admin_id = ?', [$_SESSION['admin_id']]);
            $_SESSION['unit'] = "Administrator";
        }

        unset($_SESSION['captcha']);

        echo json_encode([
            'success' => true,
            'redirect' => $link_web // URL ke halaman setelah login
        ]);
        exit;
    } else {
        $_SESSION['captcha'] = rand(1000, 9999); // regenerate
        echo json_encode(['success' => false, 'message' => 'Username atau password salah']);
        exit;
    }
}
