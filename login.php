<?php

require 'settings/route.php';

if (!isset($_SESSION['captcha'])) {
    $_SESSION['captcha'] = rand(1000, 9999);
}

if (isset($_SESSION['pengguna_id'])) {
    header('Location: '.$link_web);
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Logbook</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-in-out',
                        'slide-up': 'slideUp 0.8s ease-out',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes bounceGentle {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Hero Section dengan Login -->
    <section class="gradient-bg min-h-screen relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-20 w-32 h-32 bg-white rounded-full"></div>
            <div class="absolute top-40 right-32 w-24 h-24 bg-white rounded-full"></div>
            <div class="absolute bottom-32 left-1/4 w-16 h-16 bg-white rounded-full"></div>
        </div>

        <div class="container mx-auto px-6 py-12 relative z-10">
            <div class="flex flex-col lg:flex-row items-center justify-between min-h-screen">

                <!-- Left Side - Content -->
                <div class="lg:w-1/2 text-white mb-12 lg:mb-0 animate-fade-in">
                    <div class="mb-8">
                        <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                            Sistem <span class="text-yellow-300">Logbook</span>
                            <br>Karyawan Digital
                        </h1>
                        <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                            Platform modern untuk mencatat aktivitas harian, memantau kinerja,
                            dan meningkatkan produktivitas tim di lingkungan rumah sakit.
                        </p>
                    </div>

                    <!-- Features Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="glass-effect p-6 rounded-xl border border-white/20 animate-slide-up">
                            <div class="flex items-center mb-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-purple-500 rounded-lg flex items-center justify-center text-2xl mr-4">
                                    📝
                                </div>
                                <h3 class="text-gray-800 font-semibold text-lg">Pencatatan Mudah</h3>
                            </div>
                            <p class="text-gray-600 text-sm">Interface intuitif untuk mencatat aktivitas harian dengan cepat</p>
                        </div>

                        <div class="glass-effect p-6 rounded-xl border border-white/20 animate-slide-up" style="animation-delay: 0.2s;">
                            <div class="flex items-center mb-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-blue-500 rounded-lg flex items-center justify-center text-2xl mr-4">
                                    📊
                                </div>
                                <h3 class="text-gray-800 font-semibold text-lg">Analisis Real-time</h3>
                            </div>
                            <p class="text-gray-600 text-sm">Dashboard supervisor untuk monitoring dan evaluasi kinerja</p>
                        </div>

                        <div class="glass-effect p-6 rounded-xl border border-white/20 animate-slide-up" style="animation-delay: 0.4s;">
                            <div class="flex items-center mb-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-pink-500 rounded-lg flex items-center justify-center text-2xl mr-4">
                                    🔒
                                </div>
                                <h3 class="text-gray-800 font-semibold text-lg">Keamanan Terjamin</h3>
                            </div>
                            <p class="text-gray-600 text-sm">Sistem autentikasi berlapis dengan captcha dan role management</p>
                        </div>

                        <div class="glass-effect p-6 rounded-xl border border-white/20 animate-slide-up" style="animation-delay: 0.6s;">
                            <div class="flex items-center mb-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-orange-400 to-red-500 rounded-lg flex items-center justify-center text-2xl mr-4">
                                    ⚡
                                </div>
                                <h3 class="text-gray-800 font-semibold text-lg">Performa Optimal</h3>
                            </div>
                            <p class="text-gray-600 text-sm">Akses cepat dan responsif di semua perangkat</p>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="flex flex-wrap gap-8 text-center">
                        <div class="animate-bounce-gentle">
                            <div class="text-3xl font-bold text-yellow-300">280+</div>
                            <div class="text-blue-100 text-sm">Logbook Aktif</div>
                        </div>
                        <div class="animate-bounce-gentle" style="animation-delay: 0.5s;">
                            <div class="text-3xl font-bold text-yellow-300">70+</div>
                            <div class="text-blue-100 text-sm">Karyawan Terdaftar</div>
                        </div>
                        <div class="animate-bounce-gentle" style="animation-delay: 1s;">
                            <div class="text-3xl font-bold text-yellow-300">100%</div>
                            <div class="text-blue-100 text-sm">Keamanan Data</div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Login Form -->
                <div class="lg:w-1/2 lg:pl-12 w-full max-w-md mx-auto">
                    <div class="glass-effect p-8 rounded-2xl shadow-2xl border border-white/20 animate-slide-up">
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                                🏥
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang Kembali</h2>
                            <p class="text-gray-600">Masuk ke akun Anda untuk melanjutkan</p>
                        </div>

                        <!-- Error Message -->
                        <div id="error-message" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                            <div class="flex items-center">
                                <span class="mr-2">⚠️</span>
                                <span>Username atau password salah</span>
                            </div>
                        </div>

                        <form id="loginForm" class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                <div class="relative">
                                    <input type="text" name="username" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 pl-12"
                                        placeholder="Masukkan username Anda" />
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        👤
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 pl-12 pr-12"
                                        placeholder="Masukkan password Anda" />
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        🔐
                                    </div>
                                    <button type="button" onclick="togglePassword()"
                                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                        👁️
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kode Captcha:
                                    <span class="font-bold text-blue-600 text-lg"><?= $_SESSION['captcha'] ?></span>
                                    <input type="hidden" name="kode_captcha" value="<?= $_SESSION['captcha'] ?>">
                                </label>

                                <div class="relative">
                                    <input type="text" name="captcha" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 pl-12"
                                        placeholder="Masukkan kode di atas" />
                                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                        🔢
                                    </div>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                Masuk ke Sistem
                            </button>
                        </form>

                        <div class="mt-6 text-center">
                            <p class="text-xs text-gray-500">
                                © 2024 Sistem Logbook Rumah Sakit. Semua hak dilindungi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Cara Kerja Sistem</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Proses sederhana dalam 4 langkah untuk memulai menggunakan sistem logbook
                </p>
            </div>

            <div class="max-w-6xl mx-auto">
                <div class="relative">
                    <!-- Connection Line -->
                    <div class="absolute top-1/2 left-0 right-0 h-1 bg-gradient-to-r from-blue-400 to-purple-500 hidden lg:block transform -translate-y-1/2"></div>

                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                        <!-- Step 1 -->
                        <div class="text-center relative">
                            <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 shadow-lg relative z-10">
                                📋
                            </div>
                            <div class="bg-white p-6 rounded-xl shadow-lg border-2 border-gray-100 hover:shadow-xl transition-shadow duration-300">
                                <h3 class="text-xl font-bold text-gray-800 mb-3">1. Registrasi Akun</h3>
                                <p class="text-gray-600">
                                    Admin mendaftarkan akun baru sesuai dengan role dan unit kerja masing-masing karyawan
                                </p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="text-center relative">
                            <div class="w-20 h-20 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 shadow-lg relative z-10">
                                🔐
                            </div>
                            <div class="bg-white p-6 rounded-xl shadow-lg border-2 border-gray-100 hover:shadow-xl transition-shadow duration-300">
                                <h3 class="text-xl font-bold text-gray-800 mb-3">2. Login Aman</h3>
                                <p class="text-gray-600">
                                    Masuk menggunakan username, password, dan verifikasi captcha untuk keamanan ekstra
                                </p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="text-center relative">
                            <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 shadow-lg relative z-10">
                                ✍️
                            </div>
                            <div class="bg-white p-6 rounded-xl shadow-lg border-2 border-gray-100 hover:shadow-xl transition-shadow duration-300">
                                <h3 class="text-xl font-bold text-gray-800 mb-3">3. Catat Aktivitas</h3>
                                <p class="text-gray-600">
                                    Karyawan mencatat aktivitas harian dengan mudah melalui form yang user-friendly
                                </p>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="text-center relative">
                            <div class="w-20 h-20 bg-gradient-to-r from-orange-500 to-red-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 shadow-lg relative z-10">
                                📈
                            </div>
                            <div class="bg-white p-6 rounded-xl shadow-lg border-2 border-gray-100 hover:shadow-xl transition-shadow duration-300">
                                <h3 class="text-xl font-bold text-gray-800 mb-3">4. Evaluasi & Feedback</h3>
                                <p class="text-gray-600">
                                    Supervisor menilai kinerja dan memberikan feedback untuk pengembangan karyawan
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Fitur Unggulan</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Lengkap dengan berbagai fitur modern untuk mendukung efisiensi kerja
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 group">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        📱
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Responsif & Mobile</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Akses sistem dari berbagai perangkat dengan tampilan yang optimal di desktop, tablet, maupun smartphone.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 group">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        📊
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Dashboard Analitik</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Visualisasi data kinerja dengan grafik dan chart yang mudah dipahami untuk pengambilan keputusan yang tepat.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 group">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        🔄
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Sinkronisasi Real-time</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Data tersinkronisasi secara otomatis dan real-time antar semua pengguna untuk kolaborasi yang efektif.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 group">
                    <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        📋
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Laporan Otomatis</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Generate laporan kinerja secara otomatis dengan berbagai format dan periode yang dapat dikustomisasi.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 group">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        ⚙️
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Konfigurasi Fleksibel</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sesuaikan sistem dengan kebutuhan organisasi melalui pengaturan yang mudah dan fleksibel.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 group">
                    <div class="w-16 h-16 bg-gradient-to-r from-teal-500 to-green-600 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform duration-300">
                        🔔
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Notifikasi Pintar</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sistem notifikasi otomatis untuk reminder, deadline, dan update penting lainnya.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Testimoni Pengguna</h2>
                <p class="text-xl text-gray-600">Apa kata mereka yang sudah menggunakan sistem kami</p>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-12 rounded-3xl border border-gray-100 shadow-lg">
                    <div class="text-center">
                        <div class="text-6xl mb-6">💬</div>
                        <blockquote class="text-2xl text-gray-700 font-medium italic mb-8 leading-relaxed">
                            "Sistem logbook ini benar-benar mengubah cara kami bekerja. Pencatatan aktivitas jadi lebih mudah,
                            dan supervisor bisa memberikan feedback dengan lebih efektif. Sangat membantu untuk meningkatkan
                            produktivitas tim di rumah sakit."
                        </blockquote>
                        <div class="flex items-center justify-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-2xl mr-4">
                                👩‍⚕️
                            </div>
                            <div class="text-left">
                                <div class="font-bold text-gray-800 text-lg">Dr. Sarah Wijaya</div>
                                <div class="text-gray-600">Supervisor Unit Gawat Darurat</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-20 bg-gray-900 text-white">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-bold mb-8">Butuh Bantuan?</h2>
                <p class="text-xl text-gray-300 mb-12">
                    Tim support kami siap membantu Anda 24/7 untuk memastikan sistem berjalan dengan optimal
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                            📧
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Email Support</h3>
                        <p class="text-gray-300">admin@buildapp.my.id</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                            📱
                        </div>
                        <h3 class="text-xl font-semibold mb-2">WhatsApp</h3>
                        <p class="text-gray-300">+62 851-5682-2397</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                            🕒
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Jam Operasional</h3>
                        <p class="text-gray-300">24/7 Support</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.getElementById("loginForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch("login_proses.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            const errorDiv = document.getElementById("error-message");

            if (data.success) {
                window.location.href = data.redirect; // redirect jika login berhasil
            } else {
                errorDiv.textContent = data.message;
                errorDiv.classList.remove("hidden");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    });

    // Sembunyikan error saat user mengetik
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', function () {
            document.getElementById('error-message').classList.add('hidden');
        });
    });

    function togglePassword() {
        const pass = document.getElementById("password");
        pass.type = pass.type === "password" ? "text" : "password";
    }
</script>
</body>
</html>
