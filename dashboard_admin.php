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
    // UPDATE/EDIT PEMAIN FIX
    if (isset($_POST['update_pemain'])) {
        $id_pemain = $_POST['id_pemain'];
        $no_punggung = $_POST['no_punggung'];
        $posisi = $_POST['posisi'];

        $updateSql = mysqli_query($koneksi, "UPDATE pemain SET no_punggung='$no_punggung', posisi='$posisi' WHERE id_pemain='$id_pemain'");
        header("Location: ?section=player");
        exit();
        // UPDATE/EDIT TIM FIX
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
        $id = $_POST['id'];
        $grup = $_POST['grup'];
        $id_negara = $_POST['id_negara'];
        $main = $_POST['main'];
        $menang = $_POST['menang'];
        $seri = $_POST['seri'];
        $kalah = $_POST['kalah'];
        $goal_menang = $_POST['goal_menang'];
        $goal_kalah = $_POST['goal_kalah'];
        $poin = ($menang * 3) + ($seri * 1); // Hitung poin otomatis

        $updateSql = "UPDATE klasemen SET 
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
            
        if (mysqli_query($koneksi, $updateSql)) {
            $_SESSION['pesan'] = '<div class="alert alert-success">Berhasil menyimpan perubahan!</div>';
        } else {
            $_SESSION['pesan'] = '<div class="alert alert-danger">Gagal menyimpan perubahan!</div>';
        }
        header("Location: ?section=klasemen");
        exit();
    } elseif (isset($_POST['update_skor'])) {
        $id = $_POST['id'];
        $skor1 = $_POST['skor1'];
        $skor2 = $_POST['skor2'];

        $sql = "UPDATE pertandingan SET skor1=?, skor2=?, status='selesai' WHERE id=?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("iii", $skor1, $skor2, $id);
        $stmt->execute();

        $_SESSION['pesan'] = '<div class="alert alert-success">Skor pertandingan berhasil diperbarui!</div>';
        header("Location: ?section=pertandingan");
        exit();

        // UPDATE/EDIT PREVIEW PERTANDINGAN
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

        // TAMBAH TIM BARU FIX
    } elseif (isset($_POST['add_team'])) {
        $nama_negara = $_POST['nama_negara'];
        $pelatih = $_POST['pelatih'];
        $nama_stadion = $_POST['nama_stadion'];
        $deskripsi = $_POST['deskripsi'];

        $insertSql = "INSERT INTO negara (nama_negara, pelatih, nama_stadion, deskripsi) 
                        VALUES ('$nama_negara', '$pelatih', '$nama_stadion', '$deskripsi')";
        if (mysqli_query($koneksi, $insertSql)) {
            $_SESSION['pesan'] = '<div class="alert alert-success">Berhasil menambah tim baru!</div>';
        } else {
            $_SESSION['pesan'] = '<div class="alert alert-danger">Gagal menambah tim baru!</div>';
        }
        header("Location: ?section=team");
        exit();
        // TAMBAH PEMAIN BARU FIX
    } elseif (isset($_POST['add_player'])) {
        $nama_pemain = $_POST['nama_pemain'];
        $id_negara = $_POST['id_negara'];
        $posisi = $_POST['posisi'];
        $no_punggung = $_POST['no_punggung'];

        $insertSql = mysqli_query($koneksi, "INSERT INTO pemain (nama_pemain, id_negara, posisi, no_punggung) 
                        VALUES ('$nama_pemain', '$id_negara', '$posisi', '$no_punggung')");
        header("Location: ?section=player");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['delete_id'])) {
        $delete_id = intval($_GET['delete_id']);

        // HAPUS TIM/NEGARA FIX, kalo tim itu masih ada pemain maka gabisa dihapus
        if (isset($_GET['section']) && $_GET['section'] === 'team') {
            $countResult = mysqli_query($koneksi, "SELECT COUNT(*) FROM pemain WHERE id_negara = $delete_id");
            $count = mysqli_fetch_row($countResult)[0];
            if ($count == 0) {
                mysqli_query($koneksi, "DELETE FROM negara WHERE id_negara = $delete_id");
                header("Location: ?section=team");
                exit();
            } else {
                $msg = urlencode("Gagal menghapus tim karena terdapat pemain di dalamnya! Silahkan hapus pemain terlebih dahulu.");
                header("Location: ?section=team&error_msg=$msg");
                exit();
            }
            // HAPUS PEMAIN FIX
        } elseif (isset($_GET['section']) && $_GET['section'] === 'player') {
            $deleteSql = mysqli_query($koneksi, "DELETE FROM pemain WHERE id_pemain = $delete_id");

            header("Location: ?section=player");
            exit();
        }
    }
}

$section = $_GET['section'] ?? 'profile';
$edit_id = $_GET['edit_id'] ?? null;
$edit_type = $_GET['edit_type'] ?? null;

// Get player data for edit
$player_data = null;
if ($edit_type === 'player' && $edit_id) {
    $stmt = $koneksi->prepare("SELECT pemain.*, negara.nama_negara FROM pemain JOIN negara ON pemain.id_negara = negara.id_negara WHERE pemain.id_pemain = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $player_data = $result->fetch_assoc();
    $stmt->close();
}

// Get team data for edit
$team_data = null;
if ($edit_type === 'team' && $edit_id) {
    $stmt = $koneksi->prepare("SELECT * FROM negara WHERE id_negara = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $team_data = $result->fetch_assoc();
    $stmt->close();
}

// ambil data klasemen utk diedit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT k.*, n.nama_negara 
            FROM klasemen k
            JOIN negara n ON k.id_negara = n.id_negara
            WHERE k.id=?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_data = $result->fetch_assoc();
}

// Ambil semua data klasemen dengan JOIN ke tabel negara
$sql = "SELECT k.*, n.nama_negara 
        FROM klasemen k
        JOIN negara n ON k.id_negara = n.id_negara
        ORDER BY k.grup, k.poin DESC";
$klasemen = $koneksi->query($sql);

// Ambil daftar negara untuk dropdown
$sql_negara = "SELECT id_negara, nama_negara FROM negara ORDER BY nama_negara";
$daftar_negara = $koneksi->query($sql_negara);

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $sql = "DELETE FROM klasemen WHERE id=?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
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
            <div class="logout" style="margin-top: 20px; padding-top: 20px;">
                <a href="index.php" class="btn btn-secondary btn-block" style="width: 100%;">
                    <i class="fas fa-sign-out-alt me-2"></i>Ke Halaman Utama
                </a>
            </div>
            <div class="logout" style="margin-top: 0px; padding-top: 20px;">
                <a href="logout.php" class="btn btn-danger btn-block" style="width: 100%;">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
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
                <img src="<?= $admin['profil'] ?>" class="rounded-circle" style="width: 65px; height: 45px" />
            </div>
        </div>

        <?php if ($section === 'profile'): ?>
            <div id="profile" class="content-section active">
                <div>
                    <h5>Profil</h5>
                </div>
                <div class="card p-4 mb-4">
                    <div class="d-flex align-items-center">
                        <img src="<?= $admin['profil'] ?>" class="rounded-circle me-4" style="width: 100px; height: 100px;" />
                        <div>
                            <h5><?= $admin['nama'] ?> <span class="text-warning">★★★★★</span> <small>(Admin)</small></h5>
                            <p><i class="fas fa-map-marker-alt me-2"></i><?= $admin['domisili'] ?></p>
                            <p>Bergabung: <?= $admin['tgl_bergabung'] ?> | <?= $admin['jenis_kelamin'] ?> | <?= $admin['umur'] ?> Tahun</p>
                        </div>
                    </div>
                </div>
                <div class="data row mb-4">
                    <h5>Data Yang Saya Kelola</h5>
                    <div class="col-md-3">
                        <div class="card text-center p-3">
                            <h6>Jumlah Tim</h6>
                            <h4><?= $hitungTim ?></h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center p-3">
                            <h6>Total Pemain</h6>
                            <h4><?= $hitungPemain ?></h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center p-3">
                            <h6>Total Pertandingan</h6>
                            <h4><?= $hitungPertandingan ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($edit_type === 'team' && $team_data): ?>
        <?php if (isset($_SESSION['pesan'])): ?>
            <div class="alert-container" style="margin-left: 260px; padding: 10px;">
                <?= $_SESSION['pesan'];
                unset($_SESSION['pesan']); ?>
            </div>
        <?php endif; ?>
        <div id="edit-team" class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5>Edit Tim Negara <?= htmlspecialchars($team_data['nama_negara']) ?></h5>
            </div>

            <div class="card p-4">
                <form method="POST">
                    <input type="hidden" name="id_negara" value="<?= $team_data['id_negara'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Nama Pelatih :</label>
                        <input type="text" name="pelatih" class="form-control" value="<?= htmlspecialchars($team_data['pelatih']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stadion :</label>
                        <input type="text" name="nama_stadion" class="form-control" value="<?= htmlspecialchars($team_data['nama_stadion']) ?>" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" name="update_tim" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="?section=team" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>

    <?php elseif ($edit_type === 'player' && $player_data): ?>
        <div id="edit-player" class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5>Edit Pemain: <?= htmlspecialchars($player_data['nama_pemain']) ?></h5>
            </div>

            <div class="card p-4">
                <form method="POST">
                    <input type="hidden" name="id_pemain" value="<?= $player_data['id_pemain'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Posisi</label>
                        <input type="text" name="posisi" class="form-control" value="<?= htmlspecialchars($player_data['posisi']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor Punggung</label>
                        <input type="number" name="no_punggung" class="form-control" value="<?= htmlspecialchars($player_data['no_punggung']) ?>" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" name="update_pemain" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="?section=player" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>




    <?php elseif ($section === 'team'): ?>
        <!-- error handling -->
        <?php if (isset($_GET['error_msg'])): ?>
            <div style="padding: 10px; margin-left: 300px; background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; margin-bottom: 15px;">
                <?= htmlspecialchars(urldecode($_GET['error_msg'])) ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['pesan'])): ?>
            <div class="alert-container" style="margin-left: 260px; padding: 10px;">
                <?= $_SESSION['pesan'];
                unset($_SESSION['pesan']); ?>
            </div>
        <?php endif; ?>
        <!--  -->
        <div id="team" class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5>Manajemen Tim</h5>
                <button class="btn btn-primary" onclick="document.getElementById('team-form').style.display='block'">+ Tambah Tim Baru</button>
            </div>

            <div id="team-form" style="display: none;" class="mb-4">
                <form method="POST">
                    <div class="mb-2">
                        <label>Negara</label>
                        <input type="text" class="form-control" name="nama_negara" required />
                    </div>
                    <div class="mb-2">
                        <label>Pelatih</label>
                        <input type="text" class="form-control" name="pelatih" required />
                    </div>
                    <div class="mb-2">
                        <label>Stadion</label>
                        <input type="text" class="form-control" name="nama_stadion" required />
                    </div>
                    <div class="mb-2">
                        <label>Deskripsi</label>
                        <input type="text" class="form-control" name="deskripsi" required />
                    </div>
                    <!-- <div class="mb-2">
                            <label>URL Bendera Negara</label>
                            <input type="url" class="form-control" name="bendera" required />
                        </div> -->
                    <button type="submit" name="add_team" class="btn btn-success">Tambah Tim</button>
                </form>
            </div>

            <div class="container my-4">
                <div class="row">
                    <?php
                    $result->data_seek(0);
                    if ($result && $result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                            $negara = $row['nama_negara'];
                            $bendera = "assets/default.png";

                            switch ($negara) {
                                case "Indonesia":
                                    $bendera = "assets/Indonesia.png";
                                    break;
                                case "Thailand":
                                    $bendera = "assets/Thailand.png";
                                    break;
                                case "Vietnam":
                                    $bendera = "assets/Vietnam.webp";
                                    break;
                                case "Philippines":
                                    $bendera = "assets/Philippines.png";
                                    break;
                                case "Malaysia":
                                    $bendera = "assets/Malaysia.webp";
                                    break;
                                case "Myanmar":
                                    $bendera = "assets/Myanmar.png";
                                    break;
                                case "Laos":
                                    $bendera = "assets/Laos.png";
                                    break;
                                case "Singapore":
                                    $bendera = "assets/Singapore.webp";
                                    break;
                                case "Timor-Leste":
                                    $bendera = "assets/East_Timor.png";
                                    break;
                                case "Cambodia":
                                    $bendera = "assets/Cambodia.png";
                                    break;
                            }
                    ?>
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <img src="<?= $bendera ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-judul"><?= htmlspecialchars($row['nama_negara']) ?></h5>
                                        <p class="card-text">Pelatih: <?= htmlspecialchars($row['pelatih']) ?></p>
                                        <p class="card-text">Stadion: <?= htmlspecialchars($row['nama_stadion']) ?></p>
                                        <a href="?section=team&edit_type=team&edit_id=<?= $row['id_negara'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="?section=team&delete_id=<?= $row['id_negara'] ?>" class="btn btn-danger btn-sm">Hapus Tim</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="alert alert-danger text-center mt-5" role="alert">
                            Data negara tidak ditemukan.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php elseif ($section === 'player'): ?>
        <div id="player" class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5>Manajemen Pemain</h5>
                <button class="btn btn-primary" onclick="document.getElementById('player-form').style.display='block'">+ Tambah Pemain Baru</button>
            </div>

            <div id="player-form" style="display: none;" class="mb-4">
                <form method="POST">
                    <div class="mb-2">
                        <label>Nama Pemain</label>
                        <input type="text" class="form-control" name="nama_pemain" required />
                    </div>
                    <div class="mb-2">
                        <label>Negara</label>
                        <select class="form-control" name="id_negara" required>
                            <option value="">Pilih Negara</option>
                            <?php

                            $countrySql = "SELECT * FROM negara";
                            $countryResult = $koneksi->query($countrySql);
                            while ($country = $countryResult->fetch_assoc()) {
                                echo '<option value="' . $country['id_negara'] . '">' . htmlspecialchars($country['negara']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Posisi</label>
                        <input type="text" class="form-control" name="posisi" required />
                    </div>
                    <div class="mb-2">
                        <label>Nomor Punggung</label>
                        <input type="number" class="form-control" name="no_punggung" required />
                    </div>
                    <button type="submit" name="add_player" class="btn btn-success">Tambah Pemain</button>
                </form>
            </div>


            <div class="container my-4">
                <?php if (!empty($pemainByNegara)): ?>
                    <?php foreach ($pemainByNegara as $negara => $pemain): ?>
                        <h5><?= htmlspecialchars($negara) ?></h5>
                        <div class="row">
                            <?php foreach ($pemain as $row): ?>
                                <div class="col-md-4 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-judul"><?= htmlspecialchars($row['nama_pemain']) ?></h5>
                                            <p class="card-text">Posisi: <?= htmlspecialchars($row['posisi']) ?></p>
                                            <p class="card-text">Nomor Punggung: <?= htmlspecialchars($row['no_punggung']) ?></p>
                                            <a href="?section=player&edit_type=player&edit_id=<?= $row['id_pemain'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="?section=player&delete_id=<?= $row['id_pemain'] ?>" class="btn btn-danger btn-sm">Hapus Pemain</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-danger text-center mt-5" role="alert">
                        Data pemain tidak ditemukan.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php elseif ($section === 'pertandingan'): ?>
        <div id="pertandingan" class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5>Kelola Jadwal Pertandingan</h5>
            </div>

            <?php if (isset($_SESSION['pesan'])): ?>
                <div class="alert alert-success"><?= $_SESSION['pesan'];
                                                    unset($_SESSION['pesan']); ?></div>
            <?php endif; ?>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pertandingan</th>
                        <th>Lokasi</th>
                        <th>Skor</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM pertandingan ORDER BY tanggal ASC";
                    $pertandingan = $koneksi->query($sql);
                    while ($row = $pertandingan->fetch_assoc()): ?>
                        <tr>
                            <td><?= date('d F Y', strtotime($row['tanggal'])) ?></td>
                            <td>
                                <?= $row['team1'] ?> vs <?= $row['team2'] ?>
                            </td>
                            <td><?= $row['lokasi'] ?></td>
                            <td>
                                <?php if ($row['status'] === 'selesai'): ?>
                                    <?= $row['skor1'] ?> - <?= $row['skor2'] ?>
                                <?php else: ?>
                                    Belum dimulai
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
                                    Edit Skor
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Edit Skor -->
                        <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Skor Pertandingan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="POST">
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <div class="row">
                                                <div class="col-md-5 text-end">
                                                    <h5><?= $row['team1'] ?></h5>
                                                    <input type="number" name="skor1" class="form-control" value="<?= $row['skor1'] ?? 0 ?>" min="0">
                                                </div>
                                                <div class="col-md-2 text-center">
                                                    <h3>VS</h3>
                                                </div>
                                                <div class="col-md-5">
                                                    <h5><?= $row['team2'] ?></h5>
                                                    <input type="number" name="skor2" class="form-control" value="<?= $row['skor2'] ?? 0 ?>" min="0">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" name="update_skor" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>


    <?php elseif ($section === 'preview'): ?>
        <style>
            .container {
                max-width: 700px;
            }

            .preview-block {
                margin-bottom: 2rem;
                padding: 1.5rem;
                border-radius: 0.75rem;
                border: 1px solid #ddd;
                background-color: white;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
        </style>

        <div id="preview" class="content-section">
            <h5>Edit Preview Pertandingan</h5>
            <?php if (isset($_SESSION['pesan'])): ?>
                <div class="alert-container" style="margin-left: 0px; padding: 10px;">
                    <?= $_SESSION['pesan'];
                    unset($_SESSION['pesan']); ?>
                </div>
            <?php endif; ?>


            <?php
            // Fetch previews from the database
            $previewSql = "SELECT * FROM previews ORDER BY id ASC";
            $previewResult = $koneksi->query($previewSql);

            if ($previewResult && $previewResult->num_rows > 0):
                while ($preview = $previewResult->fetch_assoc()): ?>
                    <form class="preview-form" method="POST">
                        <div class="preview-block" data-id="<?= $preview['id'] ?>">
                            <input type="hidden" name="id" value="<?= $preview['id'] ?>" />
                            <div class="mb-3">
                                <label for="judul_<?= $preview['id'] ?>" class="form-label"><b>Judul</b></label>
                                <input type="text" class="form-control" id="judul_<?= $preview['id'] ?>" name="judul" value="<?= htmlspecialchars($preview['judul']) ?>" required />
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi_<?= $preview['id'] ?>" class="form-label"><b>Deskripsi</b></label>
                                <textarea class="form-control" id="deskripsi_<?= $preview['id'] ?>" name="deskripsi" rows="3" required><?= htmlspecialchars($preview['deskripsi']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="link_youtube_<?= $preview['id'] ?>" class="form-label"><b>Link YouTube</b></label>
                                <input type="text" class="form-control" id="link_youtube_<?= $preview['id'] ?>" name="link_youtube" value="<?= htmlspecialchars($preview['link_youtube']) ?>" required />
                                <div class="form-text">Contoh: https://www.youtube.com/embed/VIDEO_ID</div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-save" name="update_preview">Simpan Perubahan</button>
                        </div>
                    </form>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-info">Tidak ada data preview pertandingan.</div>
            <?php endif; ?>
        </div>


    <?php elseif ($section === 'klasemen'): ?>
        <style>
            /* Style untuk container tabel klasemen */
            .tables-container {
                display: flex;
                gap: 30px;
                flex-wrap: wrap;
                margin-top: 20px;
            }

            /* Style untuk setiap grup klasemen */
            .tables-container>div {
                flex: 1;
                min-width: 300px;
                background: #1e1e1e;
                border-radius: 10px;
                padding: 20px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            }

            /* Style judul grup */
            .tables-container h5 {
                color: #81c784;
                margin-bottom: 15px;
                padding-bottom: 8px;
                border-bottom: 2px solid #2e7d32;
                font-weight: bold;
            }

            /* Style tabel */
            .tables-container table {
                width: 100%;
                border-collapse: collapse;
                color: white;
            }

            /* Style header tabel */
            .tables-container th {
                background-color: #2e7d32;
                color: white;
                padding: 12px 8px;
                text-align: center;
                font-weight: 600;
            }

            /* Style sel tabel */
            .tables-container td {
                padding: 10px 8px;
                text-align: center;
                border-bottom: 1px solid #333;
            }

            /* Style baris ganjil */
            .tables-container tr:nth-child(odd) {
                background-color: #252525;
            }

            /* Style baris genap */
            .tables-container tr:nth-child(even) {
                background-color: #1e1e1e;
            }

            /* Style hover baris */
            .tables-container tr:hover {
                background-color: #333;
            }

            /* Style nama tim */
            .tables-container .team-name {
                font-weight: bold;
                color: #81c784;
                text-align: left;
                padding-left: 15px;
            }

            /* Style tombol aksi */
            .tables-container .btn-sm {
                padding: 5px 10px;
                margin: 2px;
                font-size: 12px;
            }

            /* Responsive untuk mobile */
            @media (max-width: 768px) {
                .tables-container {
                    flex-direction: column;
                    gap: 20px;
                }

                .tables-container>div {
                    min-width: 100%;
                }

                .tables-container th,
                .tables-container td {
                    padding: 8px 5px;
                    font-size: 14px;
                }
            }

            /* Style untuk kolom poin */
            .tables-container td:last-child {
                font-weight: bold;
                color: #ffd600;
            }

            /* Style untuk kolom goal */
            .tables-container td:nth-last-child(2) {
                font-family: monospace;
                font-size: 15px;
            }
        </style>
        <div id="klasemen" class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5>Edit Klasemen</h5>
                <button class="btn btn-primary" onclick="document.getElementById('klasemen-form').style.display='block'">+ Tambah Data Klasemen</button>
            </div>

            <!-- Form Tambah/Edit Data -->
            <div id="klasemen-form" style="display: <?= isset($_GET['edit']) ? 'block' : 'none' ?>;" class="mb-4">
                <form method="POST" class="card p-4">
                    <input type="hidden" name="id" value="<?= $edit_data['id'] ?? 0 ?>">

                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Grup</label>
                            <select name="grup" class="form-control" required>
                                <option value="A" <?= ($edit_data['grup'] ?? '') == 'A' ? 'selected' : '' ?>>A</option>
                                <option value="B" <?= ($edit_data['grup'] ?? '') == 'B' ? 'selected' : '' ?>>B</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Negara</label>
                            <select name="id_negara" class="form-control" required>
                                <option value="">Pilih Negara</option>
                                <?php
                                // Reset pointer hasil query
                                $daftar_negara->data_seek(0);
                                while ($negara = $daftar_negara->fetch_assoc()): ?>
                                    <option value="<?= $negara['id_negara'] ?>"
                                        <?= isset($edit_data['id_negara']) && $edit_data['id_negara'] == $negara['id_negara'] ? 'selected' : '' ?>>
                                        <?= $negara['nama_negara'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-1">
                            <label class="form-label">Main</label>
                            <input type="number" name="main" class="form-control" value="<?= $edit_data['main'] ?? 0 ?>" required min="0">
                        </div>

                        <div class="col-md-1">
                            <label class="form-label">Menang</label>
                            <input type="number" name="menang" class="form-control" value="<?= $edit_data['menang'] ?? 0 ?>" required min="0">
                        </div>

                        <div class="col-md-1">
                            <label class="form-label">Seri</label>
                            <input type="number" name="seri" class="form-control" value="<?= $edit_data['seri'] ?? 0 ?>" required min="0">
                        </div>

                        <div class="col-md-1">
                            <label class="form-label">Kalah</label>
                            <input type="number" name="kalah" class="form-control" value="<?= $edit_data['kalah'] ?? 0 ?>" required min="0">
                        </div>

                        <div class="col-md-1">
                            <label class="form-label">GM</label>
                            <input type="number" name="goal_menang" class="form-control" value="<?= $edit_data['goal_menang'] ?? 0 ?>" required min="0">
                        </div>

                        <div class="col-md-1">
                            <label class="form-label">GK</label>
                            <input type="number" name="goal_kalah" class="form-control" value="<?= $edit_data['goal_kalah'] ?? 0 ?>" required min="0">
                        </div>

                        <div class="col-md-2 mt-4">
                            <button type="submit" class="btn btn-primary" name="update_klasemen">Simpan</button>
                            <?php if (isset($edit_data)): ?>
                                <a href="?section=klasemen" class="btn btn-secondary">Batal</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabel Data Klasemen -->
            <div class="tables-container">
                <?php
                $sql = "SELECT k.*, n.nama_negara 
                            FROM klasemen k
                            JOIN negara n ON k.id_negara = n.id_negara
                            ORDER BY k.grup, k.poin DESC";
                $result = $koneksi->query($sql);

                $klasemen = [];
                while ($row = $result->fetch_assoc()) {
                    $klasemen[$row['grup']][] = $row;
                }

                // Tampilkan untuk setiap grup
                foreach (['A', 'B'] as $grup) {
                    if (isset($klasemen[$grup])) {
                        echo '<div>
                                <h5>Grup ' . $grup . '</h5>
                                <table>
                                    <tr>
                                        <th>No</th>
                                        <th>Negara</th>
                                        <th>M</th>
                                        <th>M</th>
                                        <th>S</th>
                                        <th>K</th>
                                        <th>Goal</th>
                                        <th>Poin</th>
                                        <th>Aksi</th>
                                    </tr>';

                        $no = 1;
                        foreach ($klasemen[$grup] as $tim) {
                            echo '<tr>
                                        <td>' . $no . '</td>
                                        <td class="team-name">' . strtoupper($tim['nama_negara']) . '</td>
                                        <td>' . $tim['main'] . '</td>
                                        <td>' . $tim['menang'] . '</td>
                                        <td>' . $tim['seri'] . '</td>
                                        <td>' . $tim['kalah'] . '</td>
                                        <td>' . $tim['goal_menang'] . '-' . $tim['goal_kalah'] . '</td>
                                        <td>' . $tim['poin'] . '</td>
                                        <td>
                                            <a href="?section=klasemen&edit=' . $tim['id'] . '" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="?section=klasemen&hapus=' . $tim['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin hapus?\')">Hapus</a>
                                        </td>
                                    </tr>';
                            $no++;
                        }
                        echo '</table>
                            </div>';
                    }
                }
                ?>
            </div>
        </div>
        </div>
    <?php elseif ($section === 'notifikasi'): ?>
        <div id="notifikasi" class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5>Notifikasi Pertandingan</h5>
            </div>

            <?php
            // Handle form submission for sending notifications
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_notification'])) {
                $judul = $_POST['judul'];
                $pesan = $_POST['pesan'];
                $id_pertandingan = $_POST['id_pertandingan'];

                // Dapatkan semua user yang mengaktifkan notifikasi
                $sql_users = "SELECT id_user FROM users WHERE notifikasi = 1";
                $result_users = $koneksi->query($sql_users);

                $success = true;

                while ($user = $result_users->fetch_assoc()) {
                    $id_user = $user['id_user'];
                    $stmt = $koneksi->prepare("INSERT INTO notifikasi (id_user, id_pertandingan, judul, pesan) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("iiss", $id_user, $id_pertandingan, $judul, $pesan);
                    if (!$stmt->execute()) {
                        $success = false;
                    }
                }

                if ($success) {
                    $_SESSION['pesan'] = '<div class="alert alert-success">Notifikasi berhasil dikirim ke pengguna!</div>';
                } else {
                    $_SESSION['pesan'] = '<div class="alert alert-danger">Terjadi kesalahan saat mengirim notifikasi.</div>';
                }
                header("Location: ?section=notifikasi");
                exit();
            }
            ?>

            <?php if (isset($_SESSION['pesan'])): ?>
                <div class="alert-container">
                    <?= $_SESSION['pesan'];
                    unset($_SESSION['pesan']); ?>
                </div>
            <?php endif; ?>

            <div class="card p-4">
                <h5 class="mb-4">Kirim Notifikasi Popup ke Pengguna</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Notifikasi</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="pesan" class="form-label">Pesan Notifikasi</label>
                        <textarea class="form-control" id="pesan" name="pesan" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="id_pertandingan" class="form-label">Pertandingan Terkait</label>
                        <select class="form-select" id="id_pertandingan" name="id_pertandingan">
                            <option value="0">Tidak terkait pertandingan</option>
                            <?php
                            $sql_pertandingan = "SELECT id, team1, team2 FROM pertandingan ORDER BY tanggal DESC";
                            $result_pertandingan = $koneksi->query($sql_pertandingan);
                            while ($pertandingan = $result_pertandingan->fetch_assoc()):
                            ?>
                                <option value="<?= $pertandingan['id'] ?>">
                                    <?= $pertandingan['team1'] ?> vs <?= $pertandingan['team2'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" name="send_notification" class="btn btn-primary">Kirim Notifikasi</button>
                </form>
            </div>
        </div>

    <?php endif; ?>

    <script>
        // Update bendera ketika negara dipilih
        document.getElementById('negara1').addEventListener('change', function() {
            updateFlag(this.value, 1);
        });

        document.getElementById('negara2').addEventListener('change', function() {
            updateFlag(this.value, 2);
        });

        function updateFlag(countryId, teamNumber) {
            // Lakukan AJAX request untuk mendapatkan bendera
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