<?php
session_start();

if (isset($_POST['hapus'])) {
    if (isset($_POST['hapus_key'])) {
        $key = $_POST['hapus_key']; // Mengambil kunci dari form
        unset($_SESSION['dataSiswa'][$key]); // Menghapus data sesuai kunci
        header('Location: ' . $_SERVER['PHP_SELF']); // Redirect kembali ke halaman ini setelah penghapusan
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        @media print {
            .form-container {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Data Siswa</h1>

        <div class="form-container mt-4">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama:</label>
                    <input type="text" name="nama" id="nama" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="nis" class="form-label">NIS:</label>
                    <input type="number" name="nis" id="nis" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="rayon" class="form-label">Rayon:</label>
                    <input type="text" name="rayon" id="rayon" class="form-control">
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" name="tmb" class="btn btn-primary">Tambah</button>
                    <button type="button" name="cetak" onclick="window.print()" class="btn btn-secondary">Cetak</button>
                </div>
            </form>
        </div>

        <h2 class="mt-4">Daftar Siswa</h2>
        <div class="siswa-list mt-3">
        <?php
        if (!isset($_SESSION['dataSiswa'])) {
            $_SESSION['dataSiswa'] = array();
        }

        if (isset($_POST['tmb'])) {
            if ($_POST["nama"] == "" || $_POST["nis"] == "" || $_POST['rayon'] == "") {
                echo "<div class='alert alert-warning'>Data kosong</div>";
            } else {
                $siswa = array(
                    "nama" => $_POST['nama'],
                    "nis" => $_POST['nis'],
                    "rayon" => $_POST['rayon']
                );
                $sama = false;
                foreach ($_SESSION['dataSiswa'] as $ds) {
                    if ($ds['nama'] == $siswa['nama'] && $ds['nis'] == $siswa['nis'] && $ds['rayon'] == $siswa['rayon']) {
                        $sama = true;
                        break;
                    }
                }
                if ($sama) {
                    echo "<div class='alert alert-danger'>Data ini sudah ada, tulis data lain</div>";
                } else {
                    array_push($_SESSION['dataSiswa'], $siswa);
                    echo "<div class='alert alert-success'>Data berhasil ditambahkan</div>";
                }
            }
        }
        ?>
        <?php if (!empty($_SESSION['dataSiswa'])): ?>
            <?php foreach ($_SESSION['dataSiswa'] as $key => $value): ?>
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <p class="mb-0"><?php echo htmlspecialchars($value['nama']); ?> - <?php echo htmlspecialchars($value['nis']); ?> - <?php echo htmlspecialchars($value['rayon']); ?></p>
                        <div>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="hapus_key" value="<?php echo $key; ?>">
                                <button type="submit" name="hapus" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                            <form method="get" action="edit.php" style="display:inline;">
                                <input type="hidden" name="key" value="<?php echo $key; ?>">
                                <button type="submit" class="btn btn-warning btn-sm">Ubah</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Tidak ada data siswa.</p>
        <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
