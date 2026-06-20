<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Jadwal</title>
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
            <li class="nav-item"><a class="nav-link" href="dosen.php">Dosen</a></li>
            <li class="nav-item"><a class="nav-link" href="matkul.php">Mata Kuliah</a></li>
            <li class="nav-item"><a class="nav-link active" href="jadwal.php">Jadwal</a></li>
        </ul>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manajemen Jadwal</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#jadwalModal" onclick="siapkanTambahJadwal()">Tambah Jadwal</button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr><th>No</th><th>Dosen</th><th>Mata Kuliah</th><th>Waktu</th><th>Ruang</th><th>Aksi</th></tr>
                        </thead>
                        <tbody id="tempat-data-jadwal"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="jadwalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleJadwal">Form Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formJadwal" onsubmit="simpanJadwal(event)">
                    <div class="modal-body">
                        <input type="hidden" id="jadwal_id" name="id">
                        <div class="mb-3">
                            <label>Dosen</label>
                            <select class="form-control" id="id_dosen" name="id_dosen" required>
                                <option value="">Pilih Dosen</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Mata Kuliah</label>
                            <select class="form-control" id="id_matkul" name="id_matkul" required>
                                <option value="">Pilih Mata Kuliah</option>
                            </select>
                        </div>
                        <div class="mb-3"><label>Waktu</label><input type="datetime-local" class="form-control" id="waktu" name="waktu" required></div>
                        <div class="mb-3"><label>Ruang</label><input type="text" class="form-control" id="ruang" name="ruang" required></div>
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
</body>
</html>