<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Buat Akun Admin</title>
  <link rel="stylesheet" href="login.css" />
</head>
<style>
  body {
    background-image: url('assets/backgroundLogin2.png') !important;
  }
</style>

<body>
  <div class="login-container">
    <h2>Buat Akun User</h2>

    <?php
    include 'koneksi.php';
    $pesan_error = "";
    $pesan_sukses = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nama = $_POST['nama'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      $konfirmasi = $_POST['konfirmasi'];

      if ($password !== $konfirmasi) {
        $pesan_error = "Password dan Konfirmasi tidak sama!";
      } else {
        // buat cek apakah email udah terdaftar ap blm
        $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($cek) > 0) {
          $pesan_error = "Email sudah terdaftar!";
        } else {
          $query = "INSERT INTO users (nama, email, password) VALUES ('$nama', '$email', '$password')";
          if (mysqli_query($koneksi, $query)) {
            $pesan_sukses = "Akun berhasil dibuat! Silakan login.";
          } else {
            $pesan_error = "Gagal membuat akun.";
          }
        }
      }
    }

    if (!empty($pesan_error)) {
      echo "<p class='error'>" . htmlspecialchars($pesan_error) . "</p>";
    }

    if (!empty($pesan_sukses)) {
      echo "<p class='success'>" . htmlspecialchars($pesan_sukses) . "</p>";
    }
    ?>

    <form action="create.php" method="POST">
      <input type="text" name="nama" placeholder="Nama Lengkap" required />
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <input type="password" name="konfirmasi" placeholder="Konfirmasi Password" required />
      <button type="submit">Buat Akun</button>
      <div style="text-align: center; margin-top: 20px;">
        <span>Sudah punya akun?</span>
        <a href="login2.php" style="color: #baedcd;">Login di sini</a>
      </div>
    </form>
  </div>

</body>

</html>