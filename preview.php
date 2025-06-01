<?php
include 'navbar.php';
include 'koneksi.php';

$sql = "SELECT * FROM previews";
$resultSql = $koneksi->query($sql);

// buat konversi URL yutub ke format embed
function convertToEmbedUrl($url)
{
    if (strpos($url, 'watch?v=') !== false) {
        parse_str(parse_url($url, PHP_URL_QUERY), $query);
        return 'https://www.youtube.com/embed/' . $query['v'];
    }
    return $url;
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Preview</title>
    <link rel="stylesheet" href="stylee.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Boldonse&family=Caveat+Brush&family=Merienda:wght@300..900&family=Moon+Dance&family=Rubik+Bubbles&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    body {
        background-color: black;
        padding-top: 56px;
    }

    a {
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .page-header {
        margin: 2rem 0 3rem 0;
        color: white;
        position: relative;
        display: inline-block;
    }

    .card {
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover,
    .card:focus-within {
        transform: translateY(-10px);
        box-shadow: 0 16px 32px rgba(37, 253, 13, 0.79);
    }

    .card-title {
        font-weight: 700;
        font-size: 1.25rem;
    }

    .card-title a {
        color: black;
    }

    .btn-primary {
        background-color: rgba(91, 248, 0, 0.83);
        border: none;
        border-radius: 100px;
    }

    .btn-primary:hover,
    .btn-primary:focus {
        background-color: rgb(0, 0, 0);
    }

    iframe {
        width: 100%;
        height: 220px;
        border-radius: 12px;
    }
</style>

<body>
    <main class="container mt-4 mb-5">
        <h3 class="page-header"><b>Preview Pertandingan</b></h3>
        <div class="row g-4">
            <?php
            $i = 0;
            while ($data = $resultSql->fetch_assoc()) :
                $embedUrl = convertToEmbedUrl($data['link_youtube']);
            ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="card h-100">
                        <iframe
                            src="<?= $embedUrl ?>"
                            title="<?= htmlspecialchars($data['judul']) ?>"
                            allowfullscreen
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            class="<?= $i == 0 ? 'featured' : '' ?>"></iframe>
                        <div class="card-body d-flex flex-column">
                            <h2 class="card-title">
                                <a href="<?= $data['link_youtube'] ?>" target="_blank" rel="noopener noreferrer">
                                    <?= htmlspecialchars($data['judul']) ?>
                                </a>
                            </h2>
                            <p class="card-text"><?= htmlspecialchars($data['deskripsi']) ?></p>
                            <a href="<?= $data['link_youtube'] ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary mt-auto align-self-start">
                                Tonton di YouTube
                            </a>
                        </div>
                    </article>
                </div>
            <?php
                $i++;
            endwhile;
            ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>