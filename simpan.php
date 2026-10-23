<?php
// Fungsi statis untuk menghitung total estimasi nilai aset
function hitungNilaiAset($jumlah, $harga) {
    return $jumlah * $harga;
}

// Escape teks sebelum ditampilkan di HTML
function e($teks) {
    return htmlspecialchars((string) $teks, ENT_QUOTES, "UTF-8");
}

// Halaman ini hanya boleh diakses lewat pengiriman form (POST)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// Tangkap data dari $_POST
$kode     = trim($_POST["kode"] ?? "");
$nama     = trim($_POST["nama"] ?? "");
$kategori = trim($_POST["kategori"] ?? "");
$kondisi  = trim($_POST["kondisi"] ?? "");
$jumlah   = (int) ($_POST["jumlah"] ?? 0);
$harga    = (float) ($_POST["harga"] ?? 0);

// Validasi ulang di sisi server
$errors = [];
if (!preg_match('/^BRG-\d+$/', $kode)) {
    $errors[] = "Kode harus diawali BRG- lalu angka, contoh: BRG-001.";
}
if ($nama === "") {
    $errors[] = "Nama barang wajib diisi.";
}
if ($jumlah <= 0) {
    $errors[] = "Jumlah harus lebih dari 0.";
}
if ($harga < 0) {
    $errors[] = "Harga satuan tidak boleh negatif.";
}

// Baca data yang sudah tersimpan
$file = __DIR__ . "/data.json";
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
if (!is_array($data)) {
    $data = [];
}

// Cegah kode ganda (data tersimpan dan 3 data contoh di tabel)
$kodeAda = array_column($data, "kode");
array_push($kodeAda, "BRG-001", "BRG-002", "BRG-003");
if (in_array($kode, $kodeAda, true)) {
    $errors[] = "Kode $kode sudah digunakan.";
}

// Simpan kalau semua valid
if (empty($errors)) {
    $data[] = [
        "kode"     => $kode,
        "nama"     => $nama,
        "kategori" => $kategori,
        "jumlah"   => $jumlah,
        "harga"    => $harga,
        "kondisi"  => $kondisi,
    ];
    file_put_contents(
        $file,
        json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    );
}

// Hitung nilai aset memakai fungsi di atas
$nilaiAset = hitungNilaiAset($jumlah, $harga);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ringkasan Barang - SIM-Barang</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>SIM-Barang</h1>
  </header>

  <main>
    <section class="ringkasan">
      <?php if (!empty($errors)): ?>
        <h2>Data Tidak Valid</h2>
        <ul>
          <?php foreach ($errors as $pesan): ?>
            <li><?= e($pesan) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <h2>Data Berhasil Disimpan</h2>
        <table>
          <tbody>
            <tr><th>Kode</th><td><?= e($kode) ?></td></tr>
            <tr><th>Nama Barang</th><td><?= e($nama) ?></td></tr>
            <tr><th>Kategori</th><td><?= e($kategori) ?></td></tr>
            <tr><th>Jumlah</th><td><?= $jumlah ?></td></tr>
            <tr><th>Harga Satuan</th><td>Rp <?= number_format($harga, 0, ",", ".") ?></td></tr>
            <tr><th>Kondisi</th><td><?= e($kondisi) ?></td></tr>
            <tr>
              <th>Total Estimasi Nilai Aset</th>
              <td>Rp <?= number_format($nilaiAset, 0, ",", ".") ?></td>
            </tr>
          </tbody>
        </table>
      <?php endif; ?>
      <p><a href="index.php">&laquo; Kembali ke tabel inventaris</a></p>
    </section>
  </main>

  <footer>
    <p>&copy; 2026 SIM-Barang - M.Ihsan (062530801589)</p>
  </footer>
</body>
</html>