<?php
session_start();
include 'koneksi.php';

// cek apa user udah login
$userData = [];
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    $query = "SELECT * FROM users WHERE id_user = " . intval($id_user);
    $result = mysqli_query($koneksi, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
    } else {
        $userData = null;
    }
}

?>

<style>
    .navbar {
        top: 0;
    }

    .navbar-custom .dropdown-menu {
        background-color: black !important;
    }

    .navbar-custom .dropdown-item {
        color: white !important;
    }

    .navbar-custom .dropdown-item:hover {
        background-color: #333 !important;
    }

    .navbar-custom .nav-link {
        color: white !important;
    }

    .navbar-custom .nav-link:hover {
        color: rgb(137, 255, 100) !important;
    }

    .profile-pic {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
    }

    .profile-dropdown {
        margin-left: auto;
    }
</style>

<nav class="navbar navbar-expand-lg bg-dark navbar-dark navbar-custom position-fixed w-100" style="z-index: 999;">
    <div class="container-fluid" style="background-color: black;">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#">
            <img src="assets/logo2.png" alt="Logo" width="40">
            <img src="assets/logolagi.png" alt="Logo" width="80">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Beranda</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Negara
                    </a>
                    <ul class="dropdown-menu bg-dark">
                        <?php
                        $query = "SELECT nama_negara FROM negara ORDER BY nama_negara ASC";
                        $result = mysqli_query($koneksi, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<li><a class="dropdown-item" href="negara.php?nama_negara=' . urlencode($row['nama_negara']) . '">' . htmlspecialchars($row['nama_negara']) . '</a></li>';
                            }
                        } else {
                            echo '<li><a class="dropdown-item" href="#">Tidak ada data negara</a></li>';
                        }
                        ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="schedule.php">Jadwal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="result.php">Hasil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="preview.php">Preview</a>
                </li>
            </ul>
            <?php
            // ambil data profil user dari session atau database
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                echo '
                <div class="profile-dropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://www.pngkey.com/png/full/115-1150152_default-profile-picture-avatar-png-green.png" alt="Profile" class="profile-pic">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end bg-dark">
                                <li><span class="dropdown-item disabled">Halo, ' . htmlspecialchars($userData['nama'] ?? 'Admin') . '</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="dashboard_user.php">Dashboard</a></li>';

                // kalo admin, tampilkan menu admin
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                    echo '<li><a class="dropdown-item" href="dashboard_admin.php">Admin Dashboard</a></li>';
                }

                echo '
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>';
            } else {
                // kalo belom login, tampilkan tombol login
                echo '
                <div class="d-flex">
                    <a href="login2.php" class="btn btn-outline-light me-2">Login</a>
                    <a href="create.php" class="btn btn-outline-light me-2">Register</a>
                </div>';
            }
            ?>
        </div>
    </div>
</nav>