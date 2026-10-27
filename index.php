<?php
$servername = "localhost";
$username = "root";
$password = ""; // XAMPP biasanya kosong
$dbname = "latihan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$sql = "SELECT * FROM penduduk ORDER BY id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Penduduk</title>
</head>
<body>

    <h2>Data Penduduk Kecamatan</h2>

    <p>
        <a href="input/index.html">Input Data Penduduk</a>
    </p>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Kecamatan</th>
            <th>Longitude</th>
            <th>Latitude</th>
            <th>Luas</th>
            <th>Jumlah Penduduk</th>
        </tr>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['kecamatan']) ?></td>
                    <td><?= htmlspecialchars($row['longitude']) ?></td>
                    <td><?= htmlspecialchars($row['latitude']) ?></td>
                    <td><?= htmlspecialchars($row['luas']) ?></td>
                    <td align="right">
                        <?= number_format(
                            (int)$row['jumlah_penduduk'],
                            0,
                            ',',
                            '.'
                        ) ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Belum ada data penduduk.</td>
            </tr>
        <?php endif; ?>
    </table>

    <?php $conn->close(); ?>

</body>
</html>
