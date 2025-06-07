<?php
class DatabasePDO
{
    // Properti untuk koneksi database
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbName;
    public $pdo;

    // Konstruktor yang menerima nama database dan memulai koneksi
    public function __construct($db)
    {
        $this->dbName = $db;
        $this->connect();
    }

    // Fungsi untuk membuka koneksi ke database menggunakan PDO
    private function connect()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Koneksi gagal: " . $e->getMessage());
        }
    }

    // Fungsi untuk menampilkan data dari tabel
    public function tampilData($tabel, $args = [])
    {
        $kolom   = $args['kolom'] ?? '*';
        $where   = $args['where'] ?? '';
        $params  = $args['params'] ?? [];
        $orderBy = $args['orderBy'] ?? '';
        $limit   = $args['limit'] ?? '';

        $sql = "SELECT $kolom FROM $tabel";

        if (!empty($where))   $sql .= " WHERE $where";
        if (!empty($orderBy)) $sql .= " ORDER BY $orderBy";
        if (!empty($limit))   $sql .= " LIMIT $limit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fungsi untuk login
    public function login($kolom, $tabel, $where, $params)
    {
        $sql = "SELECT $kolom FROM $tabel WHERE $where";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fungsi untuk melihat data dengan filter
    public function lihatData($tabel, $kolom, $where, $params = [])
    {
        $sql = "SELECT $kolom as tampil FROM $tabel WHERE $where";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['tampil'] ?? null;
    }

    // Fungsi untuk menyisipkan data
    public function insertData($tabel, $fields, $values)
    {
        $fieldList   = implode(",", $fields);
        $placeholders = implode(",", array_fill(0, count($fields), "?"));
        $sql = "INSERT INTO $tabel ($fieldList) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }

    // Fungsi untuk memperbarui data
    public function updateData($tabel, $fields, $where, $params)
    {
        $setClause = implode(", ", array_map(fn($f) => "$f = ?", $fields));
        $sql = "UPDATE $tabel SET $setClause WHERE $where";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // Fungsi untuk menghapus data
    public function deleteData($tabel, $where, $params)
    {
        $sql = "DELETE FROM $tabel WHERE $where";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // Fungsi untuk join tabel
    public function joinTable($select, $tabelUtama, $tabelJoin, $kondisiJoin, $where = '', $params = [])
    {
        $sql = "SELECT $select FROM $tabelUtama JOIN $tabelJoin ON $kondisiJoin";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fungsi untuk menjalankan query bebas
    public function queryBebas($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fungsi untuk membuat alert JavaScript dengan redirect
    public function alert($pesan, $link)
    {
        return "<script>alert('$pesan'); window.location.href='$link';</script>";
    }

    // Fungsi untuk redirect tanpa alert
    public function alert2($link)
    {
        return "<script>window.location.href='$link';</script>";
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}

// Membuat objek koneksi ke database
$db = new DatabasePDO('logbook_magang_rs');
?>
