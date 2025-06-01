<?php
include 'koneksi.php';
include 'navbar.php';

$nama_negara = isset($_GET['nama_negara']) ? mysqli_real_escape_string($koneksi, $_GET['nama_negara']) : '';
$posisi_aktif = isset($_GET['posisi']) ? ucfirst(strtolower($_GET['posisi'])) : 'Goalkeeper';

$query = "
    SELECT pemain.nama_pemain, pemain.posisi, pemain.no_punggung, negara.nama_negara
    FROM pemain
    JOIN negara ON pemain.id_negara = negara.id_negara
    WHERE negara.nama_negara = '$nama_negara'
";

$result = mysqli_query($koneksi, $query);

$posisi_pemain = [
    'Goalkeeper' => [],
    'Defender'   => [],
    'Midfielder' => [],
    'Forward'    => [],
];

while ($row = mysqli_fetch_assoc($result)) {
    $posisi = ucfirst(strtolower($row['posisi']));
    if (isset($posisi_pemain[$posisi])) {
        $posisi_pemain[$posisi][] = [
            'nama_pemain' => $row['nama_pemain'],
            'no_punggung' => $row['no_punggung']
        ];
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Player</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://www.pixelstalk.net/wp-content/uploads/2016/10/Football-Field-Wallpapers-HD-2560x1440.jpg');
            background-size: cover;
            display: flex;
        }

        a.nav-link {
            color: white;
        }

        p,
        h3 {
            color: white;
        }

        a:hover {
            background-color: black !important;
            color: rgb(137, 255, 100) !important;
        }

        .dropdown-menu a.dropdown-item:hover {
            background-color: #000000;
            color: #fff;
        }

        .player-group {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="container" style="margin-top: 100px;">
        <ul class="nav justify-content-center nav-tabs">
            <?php foreach (array_keys($posisi_pemain) as $posisi): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $posisi_aktif == $posisi ?: '' ?>"
                        href="?nama_negara=<?= urlencode($nama_negara) ?>&posisi=<?= urlencode($posisi) ?>">
                        <?= $posisi ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="player-group">
            <h3><?= htmlspecialchars($posisi_aktif) ?>s</h3>
            <?php if (!empty($posisi_pemain[$posisi_aktif])): ?>
                <?php foreach ($posisi_pemain[$posisi_aktif] as $pemain): ?>
                    <p><?= htmlspecialchars($pemain['nama_pemain']) ?> - No. <?= htmlspecialchars($pemain['no_punggung']) ?></p>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Tidak ada pemain di posisi ini.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>