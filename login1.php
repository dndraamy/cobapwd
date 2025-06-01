<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Login Admin</title>
  <link rel="stylesheet" href="login.css" />
</head>

<body>
  <div class="login-container">
    <h2>Login Admin</h2>

    <?php
    session_start();
    include 'koneksi.php';

    $pesan_error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id_admin = $_POST['id_admin'];
      $email = $_POST['email'];
      $password = $_POST['password'];

      $cek_id = mysqli_query($koneksi, "SELECT * FROM admin WHERE id_admin='$id_admin'");

      if (mysqli_num_rows($cek_id) > 0) {
        $query = "SELECT * FROM admin WHERE id_admin='$id_admin' AND email='$email' AND password='$password'";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) > 0) {
          $_SESSION['id_admin'] = $id_admin;
          $_SESSION['logged_in'] = true;
          $_SESSION['status'] = "login_admin";
          header("Location: dashboard_admin.php");
          exit();
        } else {
          $pesan_error = "Password atau Email salah!";
        }
      } else {
        $pesan_error = "ID Admin tidak terdaftar!";
      }
    }

    if (!empty($pesan_error)) :
    ?>
      <p class="error"><?= htmlspecialchars($pesan_error) ?></p>
    <?php endif; ?>

    <form action="login1.php" method="POST">
      <input type="text" name="id_admin" placeholder="ID Admin" required />
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit" name="login">Login</button>
      <div style="text-align: center; margin-top: 20px;">
        <span>Bukan Admin?</span>
        <a href="login2.php" style="color: #abdbe3;">Login sebagai User</a>
      </div>
    </form>
  </div>

</body>

</html>