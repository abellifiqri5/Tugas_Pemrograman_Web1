<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
$active_page = 'mahasiswa';
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa - SIAKAD</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-height: 100vh;
        }
        
        /* Navbar Premium */
        .navbar-premium {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            padding: 1rem 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.6rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        /* Sidebar Premium */
        .sidebar-premium {
            background: white;
            border-radius: 28px;
            padding: 1.5rem 1rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            height: calc(100vh - 100px);
            position: sticky;
            top: 20px;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 8px;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 16px;
            text-decoration: none;
            color: #4a5568;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu a i {
            width: 24px;
            font-size: 1.2rem;
        }
        
        .sidebar-menu a:hover {
            background: linear-gradient(135deg, #667eea15, #764ba215);
            color: #667eea;
            transform: translateX(5px);
        }
        
        .sidebar-menu a.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 8px 20px rgba(102,126,234,0.3);
        }
        
        /* Content Area */
        .content-area {
            background: white;
            border-radius: 28px;
            padding: 1.8rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        }
        
        /* Header Stats */
        .stat-card-mini {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border-radius: 20px;
            padding: 1rem 1.2rem;
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .stat-card-mini:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        
        .stat-number-mini {
            font-size: 1.8rem;
            font-weight: 800;
            color: #2d3748;
        }
        
        /* Tombol */
        .btn-premium {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 14px;
            padding: 10px 24px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102,126,234,0.4);
        }
        
        .btn-outline-premium {
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-outline-premium:hover {
            border-color: #667eea;
            background: #667eea10;
        }
        
        /* Tabel Premium */
        .table-premium {
            border-radius: 20px;
            overflow: hidden;
        }
        
        .table-premium thead th {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            color: #4a5568;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
            padding: 1rem;
        }
        
        .table-premium tbody tr {
            transition: all 0.2s;
        }
        
        .table-premium tbody tr:hover {
            background: linear-gradient(135deg, #667eea05, #764ba205);
            transform: scale(1.01);
        }
        
        /* Badge */
        .badge-jurusan {
            background: linear-gradient(135deg, #667eea20, #764ba220);
            color: #667eea;
            padding: 5px 12px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        
        /* Modal Premium */
        .modal-premium .modal-content {
            border-radius: 28px;
            border: none;
        }
        
        .modal-premium .modal-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 28px 28px 0 0;
            padding: 1.5rem;
        }
        
        /* Animasi */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
        
        /* User Avatar */
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
        }
        
        /* Search Bar */
        .search-bar {
            position: relative;
        }
        
        .search-bar input {
            border-radius: 50px;
            padding: 10px 20px 10px 45px;
            border: 1px solid #e2e8f0;
            background: #f8f9fa;
            width: 250px;
        }
        
        .search-bar i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }
        
        @media (max-width: 768px) {
            .sidebar-premium {
                position: static;
                margin-bottom: 20px;
                height: auto;
            }
            .search-bar input {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- Navbar Premium -->
<nav class="navbar-premium">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-graduation-cap me-2"></i> SIAKAD
            </a>
            <div class="d-flex align-items-center gap-3">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <div class="text-white-50 small">Welcome back,</div>
                    <div class="text-white fw-bold"><?= htmlspecialchars($_SESSION['username']); ?></div>
                </div>
                <a href="logout.php" class="btn btn-outline-light rounded-pill px-4" onclick="return confirm('Yakin ingin keluar?')">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container my-4">
    <div class="row">
        <!-- Sidebar Premium -->
        <div class="col-lg-3 mb-4 mb-lg-0">
            <div class="sidebar-premium fade-in">
                <div class="text-center mb-4">
                    <div class="bg-gradient rounded-circle d-inline-flex p-3 mb-3" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                        <i class="fas fa-university fa-2x text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Menu Utama</h5>
                    <p class="text-muted small">Kelola data akademik</p>
                </div>
                <ul class="sidebar-menu">
                    <li><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="mahasiswa.php" class="active"><i class="fas fa-user-graduate"></i> Data Mahasiswa</a></li>
                    <li><a href="dosen.php"><i class="fas fa-chalkboard-user"></i> Data Dosen</a></li>
                    <li><a href="matkul.php"><i class="fas fa-book-open"></i> Mata Kuliah</a></li>
                    <li><a href="jadwal.php"><i class="fas fa-calendar-week"></i> Jadwal Kuliah</a></li>
                </ul>
                <hr class="my-3">
                <div class="mt-3 p-3 rounded-3" style="background: linear-gradient(135deg, #667eea10, #764ba210);">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i> Total Mahasiswa
                    </small>
                    <h4 class="fw-bold text-primary mt-1" id="totalMahasiswa">0</h4>
                </div>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="col-lg-9">
            <div class="content-area fade-in">
                <!-- Header -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">
                            <i class="fas fa-user-graduate me-2" style="color: #667eea;"></i> Data Mahasiswa
                        </h2>
                        <p class="text-muted mb-0">Kelola seluruh data mahasiswa aktif</p>
                    </div>
                    <button class="btn btn-premium text-white mt-2 mt-sm-0" data-bs-toggle="modal" data-bs-target="#mahasiswaModal" onclick="siapkanTambah()">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Mahasiswa
                    </button>
                </div>
                
                <!-- Search & Filter -->
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari nama, NIM, atau jurusan...">
                    </div>
                    <div>
                        <button class="btn btn-outline-premium" onclick="exportData()">
                            <i class="fas fa-download me-1"></i> Export
                        </button>
                        <button class="btn btn-outline-premium" onclick="refreshData()">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                    </div>
                </div>
                
                <!-- Tabel Data -->
                <div class="table-responsive">
                    <table class="table table-premium" id="dataTable">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">No</th>
                                <th style="width: 15%">NIM</th>
                                <th style="width: 25%">Nama Lengkap</th>
                                <th style="width: 20%">Jurusan</th>
                                <th style="width: 20%">Email</th>
                                <th class="text-center" style="width: 15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tempat-data-mahasiswa">
                            <tr><td colspan="6" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <br>Memuat data...
                            </td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Premium -->
<div class="modal fade modal-premium" id="mahasiswaModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle">
                    <i class="fas fa-user-plus me-2"></i> Form Mahasiswa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formMahasiswa" onsubmit="simpanData(event)">
                <div class="modal-body p-4">
                    <input type="hidden" id="mahasiswa_id" name="id">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">NIM <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-id-card"></i></span>
                            <input type="text" class="form-control border-start-0" id="nim" name="nim" placeholder="Contoh: 2023001001" required autocomplete="off">
                        </div>
                        <small class="text-muted">Nomor Induk Mahasiswa (unik)</small>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control border-start-0" id="nama" name="nama" placeholder="Contoh: Ahmad Fauzan" required autocomplete="off">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Jurusan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-building"></i></span>
                            <select class="form-select border-start-0" id="jurusan" name="jurusan" required>
                                <option value="">Pilih Jurusan</option>
                                <option value="Teknik Informatika">💻 Teknik Informatika</option>
                                <option value="Sistem Informasi">📊 Sistem Informasi</option>
                                <option value="Teknik Komputer">🖥️ Teknik Komputer</option>
                                <option value="Manajemen Informatika">📚 Manajemen Informatika</option>
                                <option value="Komputerisasi Akuntansi">📈 Komputerisasi Akuntansi</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control border-start-0" id="email" name="email" placeholder="contoh@email.com" required autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-premium text-white rounded-pill px-4" id="btnSimpan">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="script.js"></script>

<script>
let dataTable;

function loadData() {
    fetch('api.php?action=list')
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalMahasiswa').innerText = data.length;
            let html = '';
            if (data.length === 0) {
                html = `<tr><td colspan="6" class="text-center py-5 text-muted">📭 Belum ada data mahasiswa. Silakan tambah data baru.</td></tr>`;
            } else {
                data.forEach((mhs, index) => {
                    html += `
                        <tr>
                            <td class="text-center">${index + 1}</td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-dark p-2">${escapeHtml(mhs.nim)}</span></td>
                            <td><strong>${escapeHtml(mhs.nama)}</strong></td>
                            <td><span class="badge-jurusan">${escapeHtml(mhs.jurusan)}</span></td>
                            <td><small><i class="fas fa-envelope me-1 text-muted"></i> ${escapeHtml(mhs.email)}</small></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning rounded-pill me-1" onclick="siapkanEdit(${mhs.id})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger rounded-pill" onclick="hapusData(${mhs.id})" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
            document.getElementById('tempat-data-mahasiswa').innerHTML = html;
            
            if (dataTable) {
                dataTable.destroy();
            }
            dataTable = $('#dataTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
                },
                pageLength: 10,
                order: [[0, 'asc']]
            });
        })
        .catch(err => {
            console.error(err);
            document.getElementById('tempat-data-mahasiswa').innerHTML = `<tr><td colspan="6" class="text-center py-5 text-danger">❌ Gagal memuat data. Pastikan api.php berjalan.</td></tr>`;
        });
}

function simpanData(event) {
    event.preventDefault();
    const form = document.getElementById('formMahasiswa');
    const formData = new FormData(form);
    
    const btn = document.getElementById('btnSimpan');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
    
    fetch('api.php?action=save', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data mahasiswa berhasil disimpan',
                timer: 1500,
                showConfirmButton: false
            });
            bootstrap.Modal.getInstance(document.getElementById('mahasiswaModal')).hide();
            loadData();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: res.message || 'Terjadi kesalahan'
            });
        }
    })
    .catch(err => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Gagal menyimpan data'
        });
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan';
    });
}

function siapkanTambah() {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-plus me-2"></i> Tambah Mahasiswa';
    document.getElementById('formMahasiswa').reset();
    document.getElementById('mahasiswa_id').value = '';
}

function siapkanEdit(id) {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-edit me-2"></i> Edit Mahasiswa';
    fetch(`api.php?action=get_single&id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('mahasiswa_id').value = data.id;
            document.getElementById('nim').value = data.nim;
            document.getElementById('nama').value = data.nama;
            document.getElementById('jurusan').value = data.jurusan;
            document.getElementById('email').value = data.email;
            new bootstrap.Modal(document.getElementById('mahasiswaModal')).show();
        });
}

function hapusData(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);
            
            fetch('api.php?action=delete', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(res => {
                if (res.status === 'success') {
                    Swal.fire('Terhapus!', 'Data berhasil dihapus', 'success');
                    loadData();
                } else {
                    Swal.fire('Gagal!', 'Data gagal dihapus', 'error');
                }
            });
        }
    });
}

function refreshData() {
    loadData();
    Swal.fire({
        icon: 'success',
        title: 'Data diperbarui!',
        timer: 1000,
        showConfirmButton: false
    });
}

function exportData() {
    window.location.href = 'api.php?action=export_mahasiswa';
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

document.addEventListener('DOMContentLoaded', loadData);

// Search filter
document.getElementById('searchInput')?.addEventListener('keyup', function() {
    if (dataTable) {
        dataTable.search(this.value).draw();
    }
});
</script>
</body>
</html>