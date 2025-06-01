<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Login User</title>
  <link rel="stylesheet" href="login.css" />
</head>
<style>
  body {
    background-image: url('assets/backgroundLogin2.png') !important;
  }
</style>

<body>
  <div class="login-container">
    <h2>Login User</h2>

    <?php
    session_start();
    include 'koneksi.php';

    $pesan_error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = $_POST['email'];
      $password = $_POST['password'];

      $cek_email = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");

      if (mysqli_num_rows($cek_email) > 0) {
        $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) > 0) {
          $user_data = mysqli_fetch_assoc($result);
          $_SESSION['id_user'] = $user_data['id_user'];
          $_SESSION['logged_in'] = true;
          $_SESSION['status'] = "login_user";
          header("Location: dashboard_user.php");
          exit();
        } else {
          $pesan_error = "Password atau Email salah!";
        }
      } else {
        $pesan_error = "Email tidak terdaftar!";
      }
    }

    if (!empty($pesan_error)) :
    ?>
      <p class="error"><?= htmlspecialchars($pesan_error) ?></p>
    <?php endif; ?>

    <form action="login2.php" method="POST">
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit" name="login">Login</button>
      <div style="text-align: center; margin-top: 20px;">
        <span>Belum punya akun?</span>
        <a href="create.php" style="color: #baedcd;">Buat Akun Disini</a>
        <hr>
        <span>Anda admin?</span>
        <a href="login1.php" style="color: #abdbe3;">Login sebagai admin</a>
      </div>
    </form>
  </div>

</body>

</html>