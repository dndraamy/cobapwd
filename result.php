<?php
include 'koneksi.php';
include 'navbar.php';

$sql = "SELECT * FROM pertandingan WHERE status='selesai' ORDER BY tanggal DESC";
$result = mysqli_query($koneksi, $sql);
$matches = [];

function getFlagPath($team)
{
    $flags = [
        'Indonesia' => 'assets/Indonesia.png',
        'Thailand' => 'assets/Thailand.png',
        'Vietnam' => 'assets/Vietnam.webp',
        'Philippines' => 'assets/Philippines.png',
        'Malaysia' => 'assets/Malaysia.webp',
        'Myanmar' => 'assets/Myanmar.png',
        'Laos' => 'assets/Laos.png',
        'Singapore' => 'assets/Singapore.webp',
        'Timor Leste' => 'assets/East_Timor.png',
        'Timor-Leste' => 'assets/East_Timor.png',
        'Cambodia' => 'assets/Cambodia.png',
    ];

    return $flags[$team] ?? 'assets/default.png';
}

while ($row = mysqli_fetch_assoc($result)) {
    $matches[] = [
        'team1' => $row['team1'],
        'team2' => $row['team2'],
        'location' => $row['lokasi'],
        'date' => date('d F Y', strtotime($row['tanggal'])),
        'flag1' => getFlagPath($row['team1']),
        'flag2' => getFlagPath($row['team2']),
        'skor1' => $row['skor1'],
        'skor2' => $row['skor2']
    ];
}

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
    <title>Hasil Pertandingan - SeaBall</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: black;
            color: white;
        }

        .header {
            background-color: #1b5e20;
            color: white;
            margin-top: 100px;
            padding: 15px;
            text-align: center;
            border-radius: 30px;
            margin-bottom: 30px;
        }

        .match-container {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .match-column {
            flex: 1;
        }

        .match-card {
            background-color: #263238;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .match-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(129, 199, 132, 0.2);
        }

        .location {
            color: #a5d6a7;
            font-size: 0.9rem;
            margin-bottom: 10px;
            text-align: center;
        }

        .match-teams {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .team {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .team-left {
            justify-content: flex-end;
            text-align: right;
        }

        .team-right {
            justify-content: flex-start;
            text-align: left;
        }

        .team-name {
            margin: 0 10px;
        }

        .score {
            font-size: 1.8rem;
            font-weight: bold;
            color: #81c784;
            margin: 0 15px;
            min-width: 60px;
            text-align: center;
        }

        .flag {
            width: 40px;
            height: 25px;
            object-fit: cover;
            border: 1px solid #37474f;
        }

        .date {
            color: #c8e6c9;
            font-size: 0.9rem;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h3><b>Hasil Pertandingan SEA Games</b></h3>
        </div>

        <div class="match-container">
            <!-- kiri -->
            <div class="match-column">
                <?php foreach ($matchesLeft as $match): ?>
                    <div class="match-card">
                        <div class="location"><?= htmlspecialchars($match['location']) ?></div>
                        <div class="match-teams">
                            <div class="team team-left">
                                <span class="team-name"><?= htmlspecialchars($match['team1']) ?></span>
                                <img src="<?= htmlspecialchars($match['flag1']) ?>" alt="<?= htmlspecialchars($match['team1']) ?>" class="flag">
                            </div>
                            <div class="score"><?= htmlspecialchars($match['skor1']) ?>:<?= htmlspecialchars($match['skor2']) ?></div>
                            <div class="team team-right">
                                <img src="<?= htmlspecialchars($match['flag2']) ?>" alt="<?= htmlspecialchars($match['team2']) ?>" class="flag">
                                <span class="team-name"><?= htmlspecialchars($match['team2']) ?></span>
                            </div>
                        </div>
                        <div class="date"><?= htmlspecialchars($match['date']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- kanan -->
            <div class="match-column">
                <?php foreach ($matchesRight as $match): ?>
                    <div class="match-card">
                        <div class="location"><?= htmlspecialchars($match['location']) ?></div>
                        <div class="match-teams">
                            <div class="team team-left">
                                <span class="team-name"><?= htmlspecialchars($match['team1']) ?></span>
                                <img src="<?= htmlspecialchars($match['flag1']) ?>" alt="<?= htmlspecialchars($match['team1']) ?>" class="flag">
                            </div>
                            <div class="score"><?= htmlspecialchars($match['skor1']) ?>:<?= htmlspecialchars($match['skor2']) ?></div>
                            <div class="team team-right">
                                <img src="<?= htmlspecialchars($match['flag2']) ?>" alt="<?= htmlspecialchars($match['team2']) ?>" class="flag">
                                <span class="team-name"><?= htmlspecialchars($match['team2']) ?></span>
                            </div>
                        </div>
                        <div class="date"><?= htmlspecialchars($match['date']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>