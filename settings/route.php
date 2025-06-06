<?php
// Menentukan protokol (https:// atau http://)
if (
    isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
) {
    $protocol = 'https://';
} else {
    $protocol = 'http://';
}

// Menentukan domain secara otomatis
$domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost'; // Cek keberadaan HTTP_HOST

// Membuat URL root secara otomatis
define('WEB_ROOT', $protocol . $domain);
$link_web = WEB_ROOT;

// Memeriksa dan memecah URL berdasarkan "/"
$url = isset($_SERVER["REQUEST_URI"]) ? explode("/", $_SERVER["REQUEST_URI"]) : [];

// Menangkap bagian-bagian URL sesuai dengan urutan
$req1 = isset($url[1]) ? $url[1] : null; // Bagian pertama setelah domain (misalnya: 'unit')
$req2 = isset($url[2]) ? $url[2] : null; // Bagian kedua (misalnya: '12')
$req3 = isset($url[3]) ? $url[3] : null; // Bagian ketiga (misalnya: 'category')
$req4 = isset($url[4]) ? $url[4] : null; // Bagian keempat
$req5 = isset($url[5]) ? $url[5] : null; // Bagian kelima
?>
