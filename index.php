<?php
include 'koneksi.php';
session_start();

$sql = "SELECT * FROM last_match ORDER BY id DESC LIMIT 1";
$match = $koneksi->query($sql)->fetch_assoc();

$notifikasi = [];
if (isset($_SESSION['user_id'])) {
    $sql = "SELECT n.*, p.team1, p.team2 
            FROM notifikasi n
            JOIN pertandingan p ON n.id_pertandingan = p.id
            WHERE n.id_user = ? AND n.dibaca = FALSE
            ORDER BY n.created_at DESC
            LIMIT 5";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $notifikasi = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SeaBall</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Boldonse&family=Caveat+Brush&family=Merienda:wght@300..900&family=Moon+Dance&family=Rubik+Bubbles&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-image: url('assets/backgroundHome.jpg');
            flex-direction: column;
            position: relative;
        }

        .main-section {
            background-size: 1300px 800px;
            background-image: url('assets/backgroundHome.jpg');
            min-height: 100vh;
        }

        .main-text {
            color: white;
            position: absolute;
            top: 12%;
            left: 46%;
            text-align: left;
        }

        a:hover {
            background-color: black !important;
            color: rgb(137, 255, 100) !important;
        }

        h1,
        h2,
        h3 {
            font-family: 'Boldonse', sans-serif;
        }

        h1 {
            padding-bottom: 20px;
        }

        p {
            font-size: 15px !important;
        }

        .feature-card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: white;
        }

        .btn-primary:hover {
            background-color: #134013;
            border-color: #134013;
        }

        .match-section {
            background-image: url('https://img.okezone.com/content/2023/05/16/51/2815184/timnas-indonesia-u-22-juara-sepakbola-sea-games-2023-ini-daftar-juara-sepakbola-sea-games-sepanjang-masa-Rk10SXJRI4.jpg');
            background-size: 800px;
            padding: 50px 0;
        }

        .match-container {
            display: flex;
            justify-content: right;
            margin-right: 50px;
        }

        .match-card {
            background-color: white;
            padding: 20px;
            width: 480px;
            text-align: center;
        }

        .team-logo {
            width: 60px;
            height: 60px;
        }

        .score-box {
            font-size: 35px;
            font-weight: bold;
            padding: 10px;
        }

        .match-card .btn {
            border-radius: 50px;
            width: 350px;
            margin: 15px;
            padding: 10px 20px;
            font-weight: bold;
            background-color: white;
            color: #001a33;
            border: 2px solid #001a33;
        }

        .match-card .btn:hover {
            background-color: #001a33;
            color: white;
        }

        body {
            background: url("https://wallpaperaccess.com/full/1288357.jpg");
        }

        #klasemen h1 {
            font-size: 24px;
            color: rgb(255, 255, 255);
            margin-top: 50px;
            text-align: center;
        }

        #klasemen .tables-container {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        #klasemen table {
            width: 48%;
            min-width: 400px;
            border-collapse: collapse;
            margin-bottom: 80px;
            box-shadow: 0 0 15px rgba(137, 255, 100, 0.3);
        }

        #klasemen th {
            background-color: #001a33;
            color: rgb(137, 255, 100);
            padding: 12px;
            text-align: center;
            font-weight: bold;
            border: 1px solid rgba(137, 255, 100, 0.2);
        }

        #klasemen td {
            background-color: rgba(0, 26, 51, 0.7);
            color: white;
            padding: 10px;
            text-align: center;
            border: 1px solid rgba(137, 255, 100, 0.1);
        }

        #klasemen tr:nth-child(even) td {
            background-color: rgba(0, 26, 51, 0.5);
        }

        #klasemen .team-name {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: left;
        }

        #klasemen .team-name img {
            width: 30px;
            height: 20px;
            object-fit: cover;
            border: 1px solid rgba(137, 255, 100, 0.5);
        }

        #klasemen hr {
            border-color: rgba(137, 255, 100, 0.3);
            margin: 40px auto;
            width: 80%;
        }

        .footer {
            background-color: rgb(0, 0, 0);
            padding: 50px 0 20px;
        }

        .footer .inner {
            display: flex;
            column-gap: 60px;
            color: white;
        }

        .main-logo {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .main-logo .logo {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }

        .main-logo .logo img {
            width: 100%;
            height: auto;
        }

        .text {
            font-size: 17px;
        }

        .footer .column {
            width: 100%;
            font-size: 14px;
        }

        .footer .column .column-title {
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.33);
        }

        .footer ul {
            padding-left: 0;
            list-style: none;
        }

        .footer ul li {
            margin-bottom: 8px;
        }

        .footer a {
            color: #ddd !important;
        }

        .footer a:hover {
            color: rgb(137, 255, 100) !important;
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1100;
        }

        .toast-header {
            background-color: #2e7d32;
            color: white;
        }
    </style>
</head>

<body>

    <?php
    include 'navbar.php';
    ?>

    <div class="main-content">
        <!-- Main Section -->
        <section class="main-section">
            <div class="container">
                <div class="main-text">
                    <h1>Menyatukan <span style="color: rgb(137, 255, 100);">ASEAN</span></h1>
                    <h1>Dengan <span style="color: rgb(137, 255, 100);">Sepak Bola</span></h1>
                    <p>Semua tentang sepak bola SEA Games: jadwal pertandingan, skor, preview,<br> dan profil tim nasional negara di Asia Tenggara dalam satu platform.</p>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-5" style="background-color: #000000;">
            <div class="container">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <h3>Jadwal Lengkap</h3>
                            <p>Lihat seluruh jadwal pertandingan sepak bola SEA Games dengan detail waktu dan lokasi.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            <h3>Preview</h3>
                            <p>Rekap dan highlight hasil pertandingan terbaru.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-flag"></i>
                            </div>
                            <h3>Profil Negara</h3>
                            <p>Informasi lengkap tentang tim nasional sepak bola negara-negara peserta.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Match Section -->
        <section class="match-section">
            <div class="match-container">
                <div class="match-card">
                    <div class="match-date"><b>FINAL SEA GAMES 2023</b></div>
                    <div class="match-title">Selasa, 15 Mei</div>
                    <div class="d-flex justify-content-around align-items-center">
                        <div>
                            <img src="assets/Indonesia.png" class="team-logo" alt="Indonesia">
                            <div>Indonesia</div>
                        </div>
                        <div class="score-box">5 - 2</div>
                        <div>
                            <img src="assets/Thailand.png" class="team-logo" alt="Thailand">
                            <div>Thailand</div>
                        </div>
                    </div>
                    <div class="match-title">National Olympic Stadium</div>
                    <a href="preview.php" class="btn">Preview Pertandingan</a>
                </div>
            </div>
        </section>

        <!-- Klasemen Section -->
        <section id="klasemen" class="container">
            <h1>KLASEMEN AKHIR SEA GAMES 2023</h1>
            <div class="tables-container">
                <?php
                $sql = "SELECT k.*, n.nama_negara, n.id_negara 
                FROM klasemen k
                JOIN negara n ON k.id_negara = n.id_negara
                ORDER BY k.grup, k.poin DESC, (k.goal_menang - k.goal_kalah) DESC, k.goal_menang DESC";
                $result = $koneksi->query($sql);

                $klasemen = [];
                while ($row = $result->fetch_assoc()) {
                    $klasemen[$row['grup']][] = $row;
                }
                foreach (['A', 'B'] as $grup) {
                    if (isset($klasemen[$grup])) {
                        echo '<div>
                    <h1>Grup ' . $grup . '</h1>
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
                        </tr>';

                        $no = 1;
                        foreach ($klasemen[$grup] as $tim) {
                            echo '<tr>
                            <td>' . $no . '</td>
                            <td class="team-name"> ' . strtoupper($tim['nama_negara']) . '</td>
                            <td>' . $tim['main'] . '</td>
                            <td>' . $tim['menang'] . '</td>
                            <td>' . $tim['seri'] . '</td>
                            <td>' . $tim['kalah'] . '</td>
                            <td>' . $tim['goal_menang'] . '-' . $tim['goal_kalah'] . '</td>
                            <td>' . $tim['poin'] . '</td>
                        </tr>';
                            $no++;
                        }

                        echo '</table>
                </div>';
                    }
                }
                ?>
            </div>
        </section>


        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="inner">
                    <div class="column">
                        <div class="main-logo">
                            <div class="logo">
                                <img src="assets/logo2.png" alt="SeaBall Logo">
                            </div>
                            <div class="text">SeaBall.</div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="column-title"><b>NAVIGASI</b></div>
                        <ul>
                            <li><a href="index.php">Beranda</a></li>
                            <li><a href="schedule.php">Jadwal Pertandingan</a></li>
                            <li><a href="result.php">Hasil Pertandingan</a></li>
                            <li><a href="preview.php">Pratinjau Pertandingan</a></li>
                        </ul>
                    </div>
                    <div class="column">
                        <div class="column-title"><b>NEGARA</b></div>
                        <ul>
                            <li><a href="negara.php?nama_negara=Indonesia">Indonesia</a></li>
                            <li><a href="negara.php?nama_negara=Thailand">Thailand</a></li>
                            <li><a href="negara.php?nama_negara=Vietnam">Vietnam</a></li>
                            <li><a href="negara.php?nama_negara=Philippines">Filipina</a></li>
                            <li><a href="negara.php?nama_negara=Malaysia">Malaysia</a></li>
                        </ul>
                    </div>
                    <div class="column">
                        <div class="column-title"><b>HUBUNGI KAMI</b></div>
                        <ul>
                            <li><i class="bi bi-envelope me-2"></i> seaball@gmail.com</li>
                            <li><i class="bi bi-telephone me-2"></i> +62 123 4567 890</li>
                            <li><i class="bi bi-geo-alt me-2"></i> Yogyakarta, Indonesia</li>
                        </ul>
                        <div class="social-media mt-3">
                            <a href="#" class="me-2"><a class="bi bi-facebook"></a></a>
                            <a href="#" class="me-2"><a class="bi bi-instagram"></a></a>
                            <a href="#" class="me-2"><a class="bi bi-youtube"></a></a>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4" style="color: #aaa;">
                    <p>© 2023 SeaBall. All Rights Reserved.</p>
                </div>
            </div>
        </footer>

        <!-- Toast Notification -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto" id="toast-title">Notifikasi Baru</strong>
                    <small id="toast-time">Baru saja</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-bell-fill text-primary me-2"></i>
                        <div>
                            <span id="toast-message">Pesan notifikasi akan muncul di sini</span>
                            <div class="text-muted small mt-1">
                                <span id="toast-team">Tim: -</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Fungsi buat cek notif terbaru
            function checkNewNotifications() {
                <?php if (isset($_SESSION['id_user'])): ?>
                    fetch('check_notifications.php?user_id=<?= $_SESSION['id_user'] ?>')
                        .then(response => response.json())
                        .then(data => {
                            if (data.unread_count > 0) {
                                // Tampilkan notifikasi popup
                                data.notifications.forEach(notif => {
                                    Swal.fire({
                                        title: notif.judul,
                                        text: notif.pesan,
                                        icon: 'info',
                                        confirmButtonText: 'OK'
                                    });
                                });

                                // Update badge notifikasi
                                if (data.unread_count > 0) {
                                    document.getElementById('notification-badge').textContent = data.unread_count;
                                    document.getElementById('notification-badge').style.display = 'inline-block';
                                }
                            }
                        });
                <?php endif; ?>
            }

            // Cek notifikasi setiap 30 detik
            setInterval(checkNewNotifications, 30000);

            // Cek saat page load
            document.addEventListener('DOMContentLoaded', function() {
                checkNewNotifications();
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>