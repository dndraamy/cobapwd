<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit();
}

$id_user = intval($_SESSION['id_user']);
$sql = "SELECT * FROM users WHERE id_user = $id_user";
$resultSql = mysqli_query($koneksi, $sql);

$user = null;
if ($resultSql && mysqli_num_rows($resultSql) > 0) {
    $user = mysqli_fetch_assoc($resultSql);
}

$sql = "SELECT * FROM negara";
$result = $koneksi->query($sql);

$sqlPlayers = "SELECT pemain.*, negara.nama_negara FROM pemain JOIN negara ON pemain.id_negara = negara.id_negara";
$resultPlayers = $koneksi->query($sqlPlayers);

$pemainByNegara = [];
if ($resultPlayers && $resultPlayers->num_rows > 0) {
    while ($row = $resultPlayers->fetch_assoc()) {
        $pemainByNegara[$row['nama_negara']][] = $row;
    }
}

$section = $_GET['section'] ?? 'profile';
$edit_id = $_GET['edit_id'] ?? null;
$edit_type = $_GET['edit_type'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f5fa;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            display: flex;
            flex-direction: column;
            color: white;
            background-image: url('assets/background_admin.png');
        }

        .sidebar .logo-section {
            background-color: #000000;
            padding-left: 25px;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .sidebar .logo-section .navbar-brand {
            display: flex;
            align-items: center;
        }

        .sidebar .nav-section {
            padding: 1rem;
        }

        .sidebar .nav-link {
            color: white;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgb(0, 0, 0);
        }

        .content {
            margin-left: 260px;
            padding: 2rem;
        }

        .content-section {
            margin: 0px 30px 0px 290px;
        }

        #profile {
            margin-left: 0px;
        }

        .data {
            width: 79rem;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo-section">
            <a class="navbar-brand" href="#">
                <img src="assets/logo2.png" alt="Logo" width="30">
                <img src="assets/logolagi2.png" alt="SeaBall" width="70">
            </a>
        </div>
        <div class="nav-section">
            <ul class="nav flex-column">
                <li>
                    <a class="nav-link <?= $section === 'profile' ? 'active' : '' ?>" href="?section=profile">Akun Saya</a>
                </li>
                <li>
                    <a class="nav-link <?= ($section === 'player' || $edit_type === 'player') ? 'active' : '' ?>" href="?section=player">Pengaturan Akun</a>
                </li>
            </ul>
            <div class="logout" style="margin-top: 300px; padding-top: 20px;">
                <a href="index.php" class="btn btn-secondary btn-block" style="width: 100%;">Ke Halaman Utama</a>
            </div>
            <div class="logout" style="margin-top: 0px; padding-top: 20px;">
                <a href="logout.php" class="btn btn-danger btn-block" style="width: 100%;">Logout</a>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 id="section-title">Dashboard User</h5>
            </div>
            <div class="d-flex align-items-center">
                <img src="https://www.pngkey.com/png/full/115-1150152_default-profile-picture-avatar-png-green.png" class="rounded-circle" style="width: 45px; height: 45px" />
            </div>
        </div>

        <?php if ($section === 'profile'): ?>
            <div id="profile" class="content-section active" style="text-align: left;">
                <div>
                    <h5>Profil</h5>
                </div>
                <div class="card p-4 mb-4">
                    <div class="d-flex align-items-center">
                        <img src="https://www.pngkey.com/png/full/115-1150152_default-profile-picture-avatar-png-green.png" style="width: 100px; height: 100px; margin-right: 30px;" />
                        <div>
                            <h5><?= $user['nama'] ?> <small>(User)</small></h5>
                            <p>Email: <?= $user['email'] ?></p>
                        </div>
                    </div>
                </div>

                <div class="data row mb-4">
                    <h5>Kelola Notifikasi</h5>
                    <?php
                    if (!isset($_SESSION['id_user'])) {
                        echo '<div class="alert alert-warning">Anda harus login untuk mengatur notifikasi.</div>';
                    } else {
                        $id_user = $_SESSION['id_user'];
                        $message = '';

                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_notifikasi'])) {
                            $status = isset($_POST['aktifkan']) ? 1 : 0;

                            $update = mysqli_query($koneksi, "UPDATE users SET notifikasi = $status WHERE id_user = $id_user");
                            $message = $update
                                ? '<div class="alert alert-success">Status notifikasi berhasil diperbarui.</div>'
                                : '<div class="alert alert-danger">Terjadi kesalahan saat memperbarui status notifikasi.</div>';
                        }

                        $result = mysqli_query($koneksi, "SELECT notifikasi FROM users WHERE id_user = $id_user");
                        $user_data = mysqli_fetch_assoc($result);
                        $current_status = $user_data ? (int)$user_data['notifikasi'] : 0;
                    ?>
                        <div class="col-md-6">
                            <?= $message ?>
                            <form method="POST">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="aktifkan" name="aktifkan" <?= $current_status ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="aktifkan">Aktifkan Notifikasi Popup</label>
                                </div>
                                <button type="submit" name="update_notifikasi" class="btn btn-primary">Simpan Pengaturan</button>
                            </form>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($section === 'player'): ?>
        <div id="settings" class="content-section">
            <div class="mb-4">
                <h4 class="fw-bold">Pengaturan Akun</h4>
            </div>
            <div class="card p-4 mb-4">
                <h5 class="mb-3">Ganti Password</h5>
                <?php
                $message = '';
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
                    $currentPassword = $_POST['currentPassword'];
                    $newPassword = $_POST['newPassword'];
                    $confirmPassword = $_POST['confirmPassword'];

                    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                        $message = '<div class="alert alert-danger">Semua data harus diisi!</div>';
                    } elseif ($newPassword !== $confirmPassword) {
                        $message = '<div class="alert alert-danger">Password baru dan konfirmasi tidak cocok!</div>';
                    } else {
                        $query = "SELECT password FROM users WHERE id_user = '$id_user'";
                        $result = mysqli_query($koneksi, $query);

                        if ($result && mysqli_num_rows($result) > 0) {
                            $user = mysqli_fetch_assoc($result);

                            if ($currentPassword === $user['password']) {
                                $updateQuery = "UPDATE users SET password = '$newPassword' WHERE id_user = '$id_user'";
                                $message = mysqli_query($koneksi, $updateQuery)
                                    ? '<div class="alert alert-success">Password berhasil diubah!</div>'
                                    : '<div class="alert alert-danger">Gagal mengubah password: ' . mysqli_error($koneksi) . '</div>';
                            } else {
                                $message = '<div class="alert alert-danger">Password saat ini salah!</div>';
                            }
                        } else {
                            $message = '<div class="alert alert-danger">User tidak ditemukan!</div>';
                        }
                    }
                }
                ?>
                <?= $message ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Password Saat Ini</label>
                        <input type="password" class="form-control" id="currentPassword" name="currentPassword" placeholder="Masukkan password saat ini" required>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Masukkan password baru (minimal 6 karakter)" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Ulangi password baru" required>
                    </div>
                    <button type="submit" name="change_password" class="btn btn-primary">Ubah Password</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</body>

</html>