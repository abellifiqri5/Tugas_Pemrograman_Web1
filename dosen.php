<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">SIAKAD Universitas</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">Halo, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong></span>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item"><a class="nav-link" href="mahasiswa.php">Mahasiswa</a></li>
            <li class="nav-item"><a class="nav-link active" href="dosen.php">Dosen</a></li>
            <li class="nav-item"><a class="nav-link" href="matkul.php">Mata Kuliah</a></li>
            <li class="nav-item"><a class="nav-link" href="jadwal.php">Jadwal</a></li>
        </ul>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manajemen Dosen</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#dosenModal" onclick="siapkanTambahDosen()">Tambah Dosen</button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-3">No</th><th>NIDN</th><th>Nama Dosen</th><th>Alamat</th><th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tempat-data-dosen"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form Dosen -->
    <div class="modal fade" id="dosenModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleDosen">Form Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formDosen" onsubmit="simpanDosen(event)">
                    <div class="modal-body">
                        <input type="hidden" id="dosen_id" name="id">
                        <div class="mb-3"><label class="form-label">NIDN</label><input type="text" class="form-control" id="nidn" name="nidn" required></div>
                        <div class="mb-3"><label class="form-label">Nama Dosen</label><input type="text" class="form-control" id="nama_dosen" name="nama" required></div>
                        <div class="mb-3"><label class="form-label">Alamat</label><textarea class="form-control" id="alamat" name="alamat" required></textarea></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        loadDosen();
    });
</script>
</body>
</html>
