<?php
include 'koneksi.php';
include 'navbar.php';

$nama_negara = $_GET['nama_negara'] ?? 'Indonesia';
$nama_negara = mysqli_real_escape_string($koneksi, $nama_negara);

$sql = "SELECT * FROM negara WHERE nama_negara = '$nama_negara'";
$result = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($result);
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SeaBall</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Boldonse&family=Caveat+Brush&family=Merienda:wght@300..900&family=Moon+Dance&family=Rubik+Bubbles&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<style>
  body {
    background-image: url('assets/background2.png') !important;
  }

  .plyr:hover {
    background-color: rgb(137, 255, 100) !important;
    color: black !important;
  }

  .plyr {
    margin-left: 20px;
    margin-top: 20px;
    border-radius: 100px;
  }
</style>

<body>

  <?php if ($data): ?>
    <div class="card mb-3 position-absolute top-50 start-50 translate-middle" style="width: 1000px;">
      <div class="row g-0">
        <div class="col-md-4 d-flex align-items-center ps-4">
          <img src="<?php echo $data['bendera']; ?>" class="img-fluid rounded-start" alt="logo" style="max-height: 250px;">
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title" style="padding: 10px 20px; font-family: Boldonse;"><?php echo $data['nama_negara']; ?></h5>
            <p class="card-text" style="padding: 0px 20px;"><?php echo $data['deskripsi']; ?></p>
            <h5 style="padding-top: 20px; margin-left: -50px;">
              <table style="border-collapse: separate; border-spacing: 70px 0;">
                <tr>
                  <td><b>Pelatih</b></td>
                  <td><b>Stadion</b></td>
                </tr>
                <tr>
                  <td><?php echo $data['pelatih']; ?></small></small></td>
                  <td><?php echo $data['nama_stadion']; ?></small></small></td>
                </tr>
              </table>
            </h5>
            <a href="pemain.php?nama_negara=<?php echo urlencode($data['nama_negara']); ?>" class="btn btn-dark plyr">Lihat pemain</a>
          </div>
        </div>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-danger text-center mt-5" role="alert">
      Data negara <strong><?php echo htmlspecialchars($nama_negara); ?></strong> tidak ditemukan.
    </div>
  <?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384..."></script>

</html>