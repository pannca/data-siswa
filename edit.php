<?php
session_start();
if (!isset($_GET['key'])) {
    header('Location: index.php');
    exit();
}

$key = $_GET['key'];

if (!isset($_SESSION['dataSiswa'][$key])) {
    header('Location: index.php');
    exit();
}

$siswa = $_SESSION['dataSiswa'][$key];

if (isset($_POST['tmb'])) {
    if ($_POST["nama"] != "" && $_POST["nis"] != "" && $_POST['rayon'] != "") {
        $_SESSION['dataSiswa'][$key] = array(
            "nama" => $_POST['nama'],
            "nis" => $_POST['nis'],
            "rayon" => $_POST['rayon']
        );
        echo "
        <script>
            alert('Data berhasil diubah');
            document.location.href = 'index.php';
        </script>
        ";
    } else {
        echo "
        <script>
            alert('Data gagal diubah');
            document.location.href = 'edit.php?key=$key';
        </script>
        ";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Ubah Data Siswa</h1>
        <form action="" method="post" class="mt-4">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama:</label>
                <input type="text" name="nama" id="nama" class="form-control" value="<?php echo htmlspecialchars($siswa['nama']); ?>">
            </div>
            <div class="mb-3">
                <label for="nis" class="form-label">NIS:</label>
                <input type="number" name="nis" id="nis" class="form-control" value="<?php echo htmlspecialchars($siswa['nis']); ?>">
            </div>
            <div class="mb-3">
                <label for="rayon" class="form-label">Rayon:</label>
                <input type="text" name="rayon" id="rayon" class="form-control" value="<?php echo htmlspecialchars($siswa['rayon']); ?>">
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" name="tmb" class="btn btn-primary">Ubah</button>
                <a href="index.php" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
