<?php
// Koneksi database
$koneksi = new mysqli("localhost", "root", "Merdeka.123", "logbook_rs");
$sql = "SELECT hambatan, solusi FROM Logbook WHERE hambatan IS NOT NULL AND hambatan != '' AND LOWER(hambatan) != 'tidak ada'";
$result = $koneksi->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// =======================
// FUNGSI UTAMA
// =======================
function preprocess($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z\s]/', '', $text);
    $text = trim(preg_replace('/\s+/', ' ', $text));
    return $text;
}

function get_bigrams($text) {
    $words = explode(' ', $text);
    $bigrams = [];
    for ($i = 0; $i < count($words) - 1; $i++) {
        $bigrams[] = $words[$i] . ' ' . $words[$i+1];
    }
    return $bigrams;
}

function rekomendasi_solusi($hambatan, $bigram_rekomendasi) {
    $clean_text = preprocess($hambatan);
    $bigrams = get_bigrams($clean_text);
    foreach ($bigrams as $bigram) {
        if (isset($bigram_rekomendasi[$bigram])) {
            return $bigram_rekomendasi[$bigram];
        }
    }
    return "Perlu ditinjau oleh supervisor.";
}

// =======================
// BANGUN PETA BIGRAM → SOLUSI
// =======================
$bigram_to_solusi = [];

foreach ($data as $row) {
    $hambatan = preprocess($row['hambatan']);
    $solusi = trim($row['solusi']);
    $bigrams = get_bigrams($hambatan);

    foreach ($bigrams as $bigram) {
        if (!isset($bigram_to_solusi[$bigram])) {
            $bigram_to_solusi[$bigram] = [];
        }
        $bigram_to_solusi[$bigram][] = $solusi;
    }
}

$bigram_rekomendasi = [];

foreach ($bigram_to_solusi as $bigram => $solusi_list) {
    $counts = array_count_values($solusi_list);
    arsort($counts);
    $bigram_rekomendasi[$bigram] = array_key_first($counts);
}

// =======================
// CONTOH PENGGUNAAN
// =======================
$input_hambatan = "tidak lengkap";
echo "<h3>Input Hambatan:</h3> $input_hambatan";
echo "<h3>Rekomendasi Solusi:</h3> " . rekomendasi_solusi($input_hambatan, $bigram_rekomendasi);
?>
