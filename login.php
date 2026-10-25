<?php
session_start();

// Data pengguna: array asosiatif username => password
$users = [
    "admin" => "ihsan270507",
    "mahasiswa"=>"270507"
];

// Kalau sudah login, langsung ke halaman utama
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    // Cocok kalau username ada di array DAN passwordnya sama
    if (isset($users[$username]) && $users[$username] === $password) {
        session_regenerate_id(true);   // ganti ID sesi supaya tidak bisa dibajak
        $_SESSION["user"] = $username; // tandai bahwa pengguna sudah login
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - SIM-Barang</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>SIM-Barang</h1>
  </header>

  <main class="halaman-login">
    <section>
      <h2>Login</h2>
      <?php if ($error !== ""): ?>
        <p class="pesan-error"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></p>
      <?php endif; ?>
      <form action="login.php" method="post">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Masuk</button>
      </form>
    </section>
  </main>

  <footer>
    <p>&copy; 2026 SIM-Barang - M.Ihsan (062530801589)</p>
  </footer>
</body>
</html>