<?php
session_start();
require 'databases/koneksi.php';
require 'settings/route.php';

if (isset($_SESSION['pengguna_id'])) {
    header('Location: '.$link_web);
    exit;
}

// Generate captcha jika belum ada
if (!isset($_SESSION['captcha'])) {
    $_SESSION['captcha'] = rand(1000, 9999);
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $captcha_input = $_POST['captcha'];

    if ($captcha_input != $_SESSION['captcha']) {
        $error = 'Captcha salah.';
        $_SESSION['captcha'] = rand(1000, 9999); // regenerate captcha
    } else {
        $pengguna = $db->tampilData('Pengguna', [
            'where' => 'username = ?',
            'params' => [$username]
        ]);

        if (!empty($pengguna) && password_verify($password, $pengguna[0]['password'])) {
            $user = $pengguna[0];
            $_SESSION['pengguna_id'] = $user['pengguna_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Cek role dan simpan data terkait
            if ($user['role'] === 'Karyawan') {
                $_SESSION['karyawan_id'] = $user['karyawan_id'];
                $_SESSION['nama'] = $db->lihatdata('Karyawan','nama','karyawan_id = ?',[$_SESSION['karyawan_id']]);
                $_SESSION['unit_id'] = $db->lihatdata('Karyawan','supervisor_id','karyawan_id = ?',[$_SESSION['karyawan_id']]);
                $_SESSION['unit'] = $db->lihatdata('Supervisor','nama','supervisor_id = ?',[$_SESSION['unit_id']]);
                // Redirect ke halaman Karyawan
                header('Location: '.$link_web);
                exit;
            } elseif ($user['role'] === 'Supervisor') {
                $_SESSION['supervisor_id'] = $user['supervisor_id'];
                $_SESSION['nama'] = $db->lihatdata('Supervisor', 'nama', 'supervisor_id = ?', [$_SESSION['supervisor_id']]);
                $_SESSION['unit_id'] = $db->lihatdata('Supervisor', 'unit_id', 'supervisor_id = ?', [$_SESSION['supervisor_id']]);
                $_SESSION['unit'] = $db->lihatdata('Unit', 'nama_unit', 'unit_id = ?', [$_SESSION['unit_id']]);
                // Redirect ke halaman Supervisor
                header('Location: '.$link_web);
                exit;
            } elseif ($user['role'] === 'Admin') {
                $_SESSION['admin_id'] = $user['admin_id'];
                $_SESSION['nama'] = $db->lihatdata('Admin', 'nama', 'admin_id = ?', [$_SESSION['admin_id']]);
                $_SESSION['unit'] = "Administrator";
                // Admin tidak perlu data unit, tetapi bisa disesuaikan jika diperlukan
                // Redirect ke halaman Admin
                header('Location: '.$link_web);
                exit;
            }

            unset($_SESSION['captcha']);
            header('Location: '.$link_web);
            exit;
        } else {
            $error = 'Username atau password salah.';
            $_SESSION['captcha'] = rand(1000, 9999);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Logbook</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen font-sans">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-gray-800">Sistem Logbook</h1>
            <p class="text-gray-500 text-sm">Silakan login untuk melanjutkan</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm text-gray-700 mb-1">Username</label>
                <input type="text" name="username" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:outline-none" />
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:outline-none" />
                    <button type="button" onclick="togglePassword()"
                        class="absolute inset-y-0 right-2 flex items-center text-sm text-gray-500">
                        👁️
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-1">Captcha: <strong><?= $_SESSION['captcha'] ?></strong></label>
                <input type="text" name="captcha" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:outline-none" />
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded shadow transition">
                Login
            </button>
        </form>

        <p class="text-center text-xs text-gray-400 mt-6">© <?= date('Y') ?> Sistem Logbook Rumah Sakit</p>
    </div>

    <script>
        function togglePassword() {
            const pass = document.getElementById("password");
            pass.type = pass.type === "password" ? "text" : "password";
        }
    </script>
</body>
</html>
