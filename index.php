<?php
session_start();

// Inisialisasi session jika belum ada
if (!isset($_SESSION['siswa'])) {
    $_SESSION['siswa'] = array();
}

// Proses tambah data siswa
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $nis = $_POST['nis'];
    $rayon = $_POST['rayon'];

    $data = array(
        'nama' => $nama,
        'nis' => $nis,
        'rayon' => $rayon
    );

    // nambahin data ke session
    array_push($_SESSION['siswa'], $data);

    // Kembalikan data yang ditambahkan sebagai JSON
    echo json_encode($data);
    exit;
}

// Proses hapus semua data siswa
if (isset($_POST['hapus'])) {
    // Lakukan aksi menghapus semua data siswa
    unset($_SESSION['siswa']);
    echo json_encode(["status" => "all_deleted"]);
    exit;
}

// Proses ngehapus data siswa individu
if (isset($_POST['hapus_individu'])) {
    $index = $_POST['hapus_individu'];
    if (isset($_SESSION['siswa'][$index])) {
        unset($_SESSION['siswa'][$index]);
        $_SESSION['siswa'] = array_values($_SESSION['siswa']); // Re-index array
    }
    echo json_encode(["status" => "deleted"]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Data Siswa</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1><center>Data Siswa</center></h1>
        <form id="siswaForm">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="nis">NIS</label>
                <input type="number" class="form-control" id="nis" name="nis" required>
            </div>
            <div class="form-group">
                <label for="rayon">Rayon</label>
                <input type="text" class="form-control" id="rayon" name="rayon" required>
            </div>
            <button type="submit" class="btn btn-outline-primary">Tambah</button>
            <button type="button" class="btn btn-outline-success" id="printData">Cetak</button>
            <button type="button" class="btn btn-outline-danger" id="hapusSemua">Hapus Semua</button>
        </form>

        <h3 class="mt-5">Data Siswa</h3>
        <table class="table table-bordered mt-3" id="siswaTable">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIS</th>
                    <th>Rayon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($_SESSION['siswa']) && !empty($_SESSION['siswa'])): ?>
                    <?php foreach ($_SESSION['siswa'] as $index => $siswa): ?>
                        <tr>
                            <td><?php echo $siswa['nama']; ?></td>
                            <td><?php echo $siswa['nis']; ?></td>
                            <td><?php echo $siswa['rayon']; ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm hapusIndividu" data-index="<?php echo $index; ?>">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.getElementById('siswaForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            var formData = new FormData(this);
            formData.append('tambah', true);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                var table = document.getElementById('siswaTable').getElementsByTagName('tbody')[0];
                var newRow = table.insertRow();
                newRow.innerHTML = `
                    <td>${data.nama}</td>
                    <td>${data.nis}</td>
                    <td>${data.rayon}</td>
                    <td>
                        <button class="btn btn-danger btn-sm hapusIndividu" data-index="${table.rows.length - 1}">Delete</button>
                    </td>
                `;
                document.getElementById('siswaForm').reset();
                attachDeleteEvent();
            })
            .catch(error => console.error('Error:', error));
        });

        document.getElementById('hapusSemua').addEventListener('click', function() {
            fetch('', {
                method: 'POST',
                body: new URLSearchParams('hapus=true')
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "all_deleted") {
                    var tableBody = document.getElementById('siswaTable').getElementsByTagName('tbody')[0];
                    tableBody.innerHTML = '';
                }
            })
            .catch(error => console.error('Error:', error));
        });

        document.getElementById('printData').addEventListener('click', function() {
            var printContent = document.getElementById('siswaTable').outerHTML;
            var originalContent = document.body.innerHTML;

            document.body.innerHTML = '<table class="table table-bordered">' + printContent + '</table>';
            window.print();
            document.body.innerHTML = originalContent;

            attachDeleteEvent();
        });

        function attachDeleteEvent() {
            var deleteButtons = document.querySelectorAll('.hapusIndividu');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    var index = this.getAttribute('data-index');
                    fetch('', {
                        method: 'POST',
                        body: new URLSearchParams('hapus_individu=' + index)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "deleted") {
                            this.closest('tr').remove();
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });
        }

        // Lampiran awal untuk tombol yang ada
        attachDeleteEvent();
    </script>
</body>

</html>
