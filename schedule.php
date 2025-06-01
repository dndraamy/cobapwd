<?php
include 'koneksi.php';
include 'navbar.php';

$nama_negara = $_GET['nama_negara'] ?? 'Indonesia';
$nama_negara = mysqli_real_escape_string($koneksi, $nama_negara);

$sql = "SELECT * FROM pertandingan ORDER BY tanggal ASC";
$result = mysqli_query($koneksi, $sql);

$sqlNegara = "SELECT * FROM negara";
$resultNegara = mysqli_query($koneksi, $sqlNegara);

$bendera_negara = [];
while ($row = mysqli_fetch_assoc($resultNegara)) {
    $bendera_negara[$row['nama_negara']] = $row['bendera'];
}

$matches = [];

while ($row = mysqli_fetch_assoc($result)) {
    $matches[] = $row;
}

// ngiitung total pertandingan trs bagi daftar pertandingan jadi 2 kolom
$total = count($matches);
$half = ceil($total / 2);
$matchesLeft = array_slice($matches, 0, $half);
$matchesRight = array_slice($matches, $half);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SeaBall</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: black;
        }

        .header {
            background-color: #1b5e20;
            color: white;
            margin-top: 100px;
            padding: 10px;
            text-align: center;
            border-radius: 30px;
        }

        .match-columns {
            display: flex;
            gap: 20px;
            margin-top: 30px;
        }

        .match-column {
            flex: 1;
        }

        .match-card {
            background-color: #263238;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            text-align: center;
            transition: box-shadow 0.3s ease;
        }

        .match-card:hover {
            box-shadow: 0 0 20px #81c784;
        }

        .lokasi {
            color: #a5d6a7;
            margin-bottom: 12px;
        }

        .teams {
            display: flex;
            justify-content: space-between;
            text-align: left;
            color: white;
        }

        .team {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            width: 140px;
            color: white;
        }

        .vs {
            padding-bottom: 10px;
            color: #81c784;
            text-align: center;
        }

        .flag {
            width: 36px;
            height: 24px;
        }

        .tanggal {
            color: #c8e6c9;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h3><b>Jadwal Pertandingan SEA Games</b></h3>
        </div>

        <div class="match-columns">
            <!-- kiri -->
            <div class="match-column">
                <?php foreach ($matchesLeft as $match): ?>
                    <div class="match-card">
                        <div class="lokasi"><?= htmlspecialchars($match['lokasi']) ?></div>
                        <div class="teams">
                            <div class="team">
                                <img src="<?= htmlspecialchars($bendera_negara[$match['team1']] ?? 'default.png') ?>" alt="<?= htmlspecialchars($match['team1']) ?> Flag" class="flag" />
                                <span><?= htmlspecialchars($match['team1']) ?></span>
                            </div>
                            <div class="vs">VS</div>
                            <div class="team" style="justify-content: flex-end;">
                                <span><?= htmlspecialchars($match['team2']) ?></span>
                                <img src="<?= htmlspecialchars($bendera_negara[$match['team2']] ?? 'default.png') ?>" alt="<?= htmlspecialchars($match['team2']) ?> Flag" class="flag" />
                            </div>
                        </div>
                        <div class="tanggal"><?= htmlspecialchars($match['tanggal']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- kanann -->
            <div class="match-column">
                <?php foreach ($matchesRight as $match): ?>
                    <div class="match-card">
                        <div class="lokasi"><?= htmlspecialchars($match['lokasi']) ?></div>
                        <div class="teams">
                            <div class="team">
                                <img src="<?= htmlspecialchars($bendera_negara[$match['team1']] ?? 'default.png') ?>" alt="<?= htmlspecialchars($match['team1']) ?> Flag" class="flag" />
                                <span><?= htmlspecialchars($match['team1']) ?></span>
                            </div>
                            <div class="vs">VS</div>
                            <div class="team">
                                <span><?= htmlspecialchars($match['team2']) ?></span>
                                <img src="<?= htmlspecialchars($bendera_negara[$match['team2']] ?? 'default.png') ?>" alt="<?= htmlspecialchars($match['team2']) ?> Flag" class="flag" />
                            </div>
                        </div>
                        <div class="tanggal"><?= htmlspecialchars($match['tanggal']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>