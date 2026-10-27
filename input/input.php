<?php
$servername = "localhost";
$username = "root";
$password = ""; // XAMPP biasanya kosong
$dbname = "latihan";

// Periksa apakah formulir dikirim menggunakan POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit;
}

// Ambil data dari formulir
$kecamatan = trim($_POST["kecamatan"] ?? "");
$longitude = $_POST["longitude"] ?? "";
$latitude = $_POST["latitude"] ?? "";
$luas = $_POST["luas"] ?? "";
$jumlah_penduduk = $_POST["jumlah_penduduk"] ?? "";

// Validasi data di sisi server
if (
    $kecamatan === "" ||
    !is_numeric($longitude) ||
    !is_numeric($latitude) ||
    !is_numeric($luas) ||
    !filter_var(
        $jumlah_penduduk,
        FILTER_VALIDATE_INT,
        ["options" => ["min_range" => 1]]
    ) ||
    (float)$longitude < -180 ||
    (float)$longitude > 180 ||
    (float)$latitude < -90 ||
    (float)$latitude > 90 ||
    (float)$luas <= 0 ||
    strlen($kecamatan) > 100
) {
    exit("Input tidak valid. Periksa kembali data yang dimasukkan.");
}

// Buat koneksi database
$conn = new mysqli(
    $servername,
    $username,
    $password,
    $dbname
);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Masukkan data ke tabel penduduk
$sql = "INSERT INTO penduduk
        (kecamatan, longitude, latitude, luas, jumlah_penduduk)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Gagal menyiapkan query: " . $conn->error);
}

$longitude = (float)$longitude;
$latitude = (float)$latitude;
$luas = (float)$luas;
$jumlah_penduduk = (int)$jumlah_penduduk;

$stmt->bind_param(
    "sdddi",
    $kecamatan,
    $longitude,
    $latitude,
    $luas,
    $jumlah_penduduk
);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();

    header("Location: ../index.php");
    exit;
} else {
    echo "Gagal menyimpan data: " .
         htmlspecialchars($stmt->error);

    $stmt->close();
    $conn->close();
}
?>
