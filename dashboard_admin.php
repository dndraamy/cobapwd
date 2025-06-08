<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM admin";
$resultSql = $koneksi->query($sql);
$admin = $resultSql->fetch_assoc();

// buat ngitung ada brp tim yg dikelola admin
$hitungTim = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM negara"))['total'];

// buat ngitung ada brp pemain yg dikelola admin
$hitungPemain = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pemain"))['total'];

// buat ngitung brp pelatih yg dikelola admin
$hitungPertandingan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(DISTINCT id) as total FROM pertandingan WHERE id IS NOT NULL AND id != ''"))['total'];

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // UPDATE/EDIT PEMAIN
    if (isset($_POST['update_pemain'])) {
        $id_pemain = $_POST['id_pemain'];
        $no_punggung = $_POST['no_punggung'];
        $posisi = $_POST['posisi'];

        $updateSql = mysqli_query($koneksi, "UPDATE pemain SET no_punggung='$no_punggung', posisi='$posisi' WHERE id_pemain='$id_pemain'");
        header("Location: ?section=player");
        exit();

        // UPDATE/EDIT TIM
    } elseif (isset($_POST['update_tim'])) {
        $id_negara = $_POST['id_negara'];
        $pelatih = $_POST['pelatih'];
        $nama_stadion = $_POST['nama_stadion'];

        $updateSql = "UPDATE negara SET pelatih='$pelatih', nama_stadion='$nama_stadion' 
                    WHERE id_negara='$id_negara'";
        if (mysqli_query($koneksi, $updateSql)) {
            $_SESSION['pesan'] = '<div class="alert alert-success">Berhasil menyimpan perubahan!</div>';
        } else {
            $_SESSION['pesan'] = '<div class="alert alert-danger">Gagal menyimpan perubahan!</div>';
        }
        header("Location: ?section=team");
        exit();

        // UPDATE KLASEMEN
    } elseif (isset($_POST['update_klasemen'])) {
        $id = intval($_POST['id']);
        $grup = mysqli_real_escape_string($koneksi, $_POST['grup']);
        $id_negara = intval($_POST['id_negara']);
        $main = $_POST['main'];
        $menang = $_POST['menang'];
        $seri = $_POST['seri'];
        $kalah = $_POST['kalah'];
        $goal_menang = $_POST['goal_menang'];
        $goal_kalah = $_POST['goal_kalah'];
        $poin = ($menang * 3) + ($seri * 1); // itung poin otomatis

        if ($id > 0) {
            // UPDATE klasemen
            $sql = "UPDATE klasemen SET 
                grup = '$grup',
                id_negara = $id_negara,
                main = $main,
                menang = $menang,
                seri = $seri,
                kalah = $kalah,
                goal_menang = $goal_menang,
                goal_kalah = $goal_kalah,
                poin = $poin
                WHERE id = $id";
        } else {
            // INSERT data baru
            $sql = "INSERT INTO klasemen (grup, id_negara, main, menang, seri, kalah, goal_menang, goal_kalah, poin)
                VALUES ('$grup', $id_negara, $main, $menang, $seri, $kalah, $goal_menang, $goal_kalah, $poin)";
        }

        if (mysqli_query($koneksi, $sql)) {
            $_SESSION['pesan'] = '<div class="alert alert-success">Data berhasil disimpan!</div>';
        } else {
            $_SESSION['pesan'] = '<div class="alert alert-danger">Gagal: ' . mysqli_error($koneksi) . '</div>';
        }
        header("Location: ?section=klasemen");
        exit();

        // EDIT SKOR
    } elseif (isset($_POST['update_skor'])) {
        $id = intval($_POST['id']);
        $skor1 = intval($_POST['skor1']);
        $skor2 = intval($_POST['skor2']);

        $sql = "UPDATE pertandingan SET skor1 = $skor1, skor2 = $skor2, status = 'selesai' WHERE id = $id";
        mysqli_query($koneksi, $sql);

        $_SESSION['pesan'] = '<div class="alert alert-success">Skor pertandingan berhasil diperbarui!</div>';
        header("Location: ?section=pertandingan");
        exit();

        // UPDATE/EDIT HALAMAN PREVIEW PERTANDINGAN
    } elseif (isset($_POST['update_preview'])) {
        $id = $_POST['id'];
        $judul = $_POST['judul'];
        $deskripsi = $_POST['deskripsi'];
        $link_youtube = $_POST['link_youtube'];

        $updateSql = "UPDATE previews SET 
                 judul = '$judul',
                 deskripsi = '$deskripsi',
                 link_youtube = '$link_youtube'
                 WHERE id = '$id'";

        if (mysqli_query($koneksi, $updateSql)) {
            $_SESSION['pesan'] = '<div class="alert alert-success">Berhasil menyimpan perubahan!</div>';
        } else {
            $_SESSION['pesan'] = '<div class="alert alert-danger">Gagal menyimpan perubahan!</div>';
        }
        header("Location: ?section=preview");
        exit();

        // UPDATE/EDIT JADWAL PERTANDINGAN
    } elseif (isset($_POST['update_jadwal'])) {
        $id = intval($_POST['id']);
        $tanggal = $_POST['tanggal'];
        $team1 = $_POST['team1'];
        $team2 = $_POST['team2'];
        $lokasi = $_POST['lokasi'];

        $sql = "UPDATE pertandingan SET 
            tanggal = '$tanggal',
            team1 = '$team1',
            team2 = '$team2',
            lokasi = '$lokasi'
            WHERE id = $id";

        if (mysqli_query($koneksi, $sql)) {
            $_SESSION['pesan'] = '<div class="alert alert-success">Jadwal pertandingan berhasil diperbarui!</div>';
        } else {
            $_SESSION['pesan'] = '<div class="alert alert-danger">Gagal memperbarui jadwal: ' . mysqli_error($koneksi) . '</div>';
        }
        header("Location: ?section=pertandingan");
        exit();

        // TAMBAH TIM BARU
    } elseif (isset($_POST['add_team'])) {
        $nama_negara = $_POST['nama_negara'];
        $pelatih = $_POST['pelatih'];
        $nama_stadion = $_POST['nama_stadion'];
        $deskripsi = $_POST['deskripsi'];
        $bendera = $_POST['bendera'];

        $insertSql = "INSERT INTO negara (nama_negara, pelatih, nama_stadion, deskripsi, bendera) 
                    VALUES ('$nama_negara', '$pelatih', '$nama_stadion', '$deskripsi', '$bendera')";
        if (mysqli_query($koneksi, $insertSql)) {
            $_SESSION['pesan'] = '<div class="alert alert-success">Berhasil menambah tim baru!</div>';
        } else {
            $_SESSION['pesan'] = '<div class="alert alert-danger">Gagal menambah tim baru!</div>';
        }
        header("Location: ?section=team");
        exit();

        // TAMBAH PEMAIN BARU
    } elseif (isset($_POST['add_player'])) {
        $nama_pemain = $_POST['nama_pemain'];
        $id_negara = $_POST['id_negara'];
        $posisi = $_POST['posisi'];
        $no_punggung = $_POST['no_punggung'];

        $insertSql = "INSERT INTO pemain (nama_pemain, id_negara, posisi, no_punggung) 
                    VALUES ('$nama_pemain', '$id_negara', '$posisi', '$no_punggung')";
        if (mysqli_query($koneksi, $insertSql)) {
            $_SESSION['pesan'] = '<div class="alert alert-success">Berhasil menambah pemain baru!</div>';
        } else {
            $_SESSION['pesan'] = '<div class="alert alert-danger">Gagal menambah pemain baru!</div>';
        }
        header("Location: ?section=player");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['delete_id'])) {
        $delete_id = intval($_GET['delete_id']);

        // HAPUS TIM/NEGARA
        if (isset($_GET['section']) && $_GET['section'] === 'team') {
            $countResult = mysqli_query($koneksi, "SELECT COUNT(*) FROM pemain WHERE id_negara = $delete_id");
            $count = mysqli_fetch_row($countResult)[0];

            if ($count == 0) {
                $deleteResult = mysqli_query($koneksi, "DELETE FROM negara WHERE id_negara = $delete_id");
                if ($deleteResult) {
                    $_SESSION['pesan'] = '<div class="alert alert-success">Tim berhasil dihapus!</div>';
                } else {
                    $_SESSION['pesan'] = '<div class="alert alert-danger">Gagal menghapus : ' . mysqli_error($koneksi) . '</div>';
                }
                header("Location: ?section=team");
                exit();
            } else {
                $_SESSION['pesan'] = '<div class="alert alert-danger">Gagal menghapus tim karena masih ada pemain yang terdaftar di tim ini. Silakan hapus pemain terlebih dahulu.</div>';
                header("Location: ?section=team");
                exit();
            }

            // HAPUS PEMAIN
        } elseif (isset($_GET['section']) && $_GET['section'] === 'player') {
            $deleteSql = mysqli_query($koneksi, "DELETE FROM pemain WHERE id_pemain = $delete_id");

            if ($deleteSql) {
                $_SESSION['pesan'] = '<div class="alert alert-success">Pemain berhasil dihapus!</div>';
            } else {
                $_SESSION['pesan'] = '<div class="alert alert-danger">Gagal menghapus pemain.</div>';
            }

            header("Location: ?section=player");
            exit();
        }
    }
}

$section = $_GET['section'] ?? 'profile';
$edit_id = $_GET['edit_id'] ?? null;
$edit_type = $_GET['edit_type'] ?? null;

// ambil data pemain buat diedit
$player_data = null;
if ($edit_type === 'player' && $edit_id) {
    $edit_id = intval($edit_id);
    $sql = "SELECT pemain.*, negara.nama_negara 
            FROM pemain 
            JOIN negara ON pemain.id_negara = negara.id_negara 
            WHERE pemain.id_pemain = $edit_id";
    $result = mysqli_query($koneksi, $sql);
    $player_data = mysqli_fetch_assoc($result);
}

// ambil data tim buat diedit
$team_data = null;
if ($edit_type === 'team' && $edit_id) {
    $edit_id = intval($edit_id);
    $sql = "SELECT * FROM negara WHERE id_negara = $edit_id";
    $result = mysqli_query($koneksi, $sql);
    $team_data = mysqli_fetch_assoc($result);
}

// ambil data klasemen buat diedit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $sql = "SELECT k.*, n.nama_negara 
            FROM klasemen k
            JOIN negara n ON k.id_negara = n.id_negara
            WHERE k.id = $id";
    $result = mysqli_query($koneksi, $sql);
    $edit_data = mysqli_fetch_assoc($result);
}

// ambil semua data klasemen dengan JOIN ke tabel negara
$sql = "SELECT k.*, n.nama_negara 
        FROM klasemen k
        JOIN negara n ON k.id_negara = n.id_negara
        ORDER BY k.grup, k.poin DESC";
$klasemen = $koneksi->query($sql);

// ambil daftar negara untuk dropdown
$sql_negara = "SELECT id_negara, nama_negara FROM negara ORDER BY nama_negara";
$daftar_negara = $koneksi->query($sql_negara);

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']); // sanitasi angka agar tetap aman
    $sql = "DELETE FROM klasemen WHERE id = $id";
    mysqli_query($koneksi, $sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
                <li><a class="nav-link <?= $section === 'profile' ? 'active' : '' ?>" href="?section=profile">Akun Saya</a></li>
                <li><a class="nav-link <?= ($section === 'team' || $edit_type === 'team') ? 'active' : '' ?>" href="?section=team">Manajemen Tim</a></li>
                <li><a class="nav-link <?= ($section === 'player' || $edit_type === 'player') ? 'active' : '' ?>" href="?section=player">Manajemen Pemain</a></li>
                <li><a class="nav-link <?= ($section === 'pertandingan') ? 'active' : '' ?>" href="?section=pertandingan">Edit Jadwal Pertandingan</a></li>
                <li><a class="nav-link <?= ($section === 'preview' || $edit_type === 'preview') ? 'active' : '' ?>" href="?section=preview">Edit Preview Pertandingan</a></li>
                <li><a class="nav-link <?= ($section === 'klasemen' || $edit_type === 'klasemen') ? 'active' : '' ?>" href="?section=klasemen">Edit Klasemen</a></li>
                <li><a class="nav-link <?= ($section === 'notifikasi' || $edit_type === 'notifikasi') ? 'active' : '' ?>" href="?section=notifikasi">Kirim Notifikasi</a></li>
            </ul>
            <div class="logout" style="margin-top: 50px; padding-top: 20px;">
                <a href="index.php" class="btn btn-secondary btn-block" style="width: 100%;">
                    <i></i>Ke Halaman Utama
                </a>
            </div>
            <div class="logout" style="margin-top: 0px; padding-top: 20px;">
                <a href="logout.php" class="btn btn-danger btn-block" style="width: 100%;">
                    <i></i>Logout
                </a>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 id="section-judul">Dashboard Admin</h5>
            </div>
            <div class="d-flex align-items-center">
                <img src="<?= $admin['profil'] ?>" class="rounded-circle" style="width: 45px; height: 45px" />
            </div>
        </div>

        <?php if ($section === 'profile'):
            include 'sections/profile_section.php'; ?>
        <?php endif; ?>
    </div>

    <?php if ($edit_type === 'team' && $team_data):
        include 'sections/edit_team.php'; ?>

    <?php elseif ($edit_type === 'player' && $player_data):
        include 'sections/edit_player.php'; ?>

    <?php elseif ($section === 'team'):
        include 'sections/team_section.php'; ?>

    <?php elseif ($section === 'player'):
        include 'sections/player_section.php'; ?>

    <?php elseif ($section === 'pertandingan'):
        include 'sections/pertandingan_section.php'; ?>

    <?php elseif ($section === 'preview'):
        include 'sections/preview_section.php'; ?>

    <?php elseif ($section === 'klasemen'):
        include 'sections/klasemen_section.php'; ?>

    <?php elseif ($section === 'notifikasi'):
        include 'sections/notifikasi_section.php'; ?>

    <?php endif; ?>

    <script>
        // update bendera pas negara dipilih
        document.getElementById('negara1').addEventListener('change', function() {
            updateFlag(this.value, 1);
        });

        document.getElementById('negara2').addEventListener('change', function() {
            updateFlag(this.value, 2);
        });

        function updateFlag(countryId, teamNumber) {
            // AJAX request buat dapetin bendera
            fetch('get_flag.php?id=' + countryId)
                .then(response => response.json())
                .then(data => {
                    if (teamNumber === 1) {
                        document.querySelector('#negara1 + img').src = data.bendera;
                    } else {
                        document.querySelector('#negara2 + img').src = data.bendera;
                    }
                });
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>