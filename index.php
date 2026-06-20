<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

$count_mahasiswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM mahasiswa"))['total'] ?? 0;
$count_dosen = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM dosen"))['total'] ?? 0;
$count_matkul = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM matkul"))['total'] ?? 0;
$count_jadwal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM jadwal"))['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SIAKAD</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        /* Navbar Styling */
        .navbar-custom {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 5px 30px rgba(0,0,0,0.1);
            border-radius: 0 0 20px 20px;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .user-name {
            font-weight: 600;
            color: #4a5568;
        }
        
        .btn-logout {
            background: linear-gradient(135deg, #f56565, #e53e3e);
            border: none;
            border-radius: 12px;
            padding: 8px 20px;
            font-weight: 500;
            transition: transform 0.2s;
        }
        
        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(229,62,62,0.3);
        }
        
        /* Card Dashboard Styling */
        .stat-card {
            background: white;
            border-radius: 24px;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
            overflow: hidden;
            position: relative;
        }
        
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .stat-card .card-body {
            padding: 1.5rem;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            margin-bottom: 15px;
        }
        
        .stat-icon.mahasiswa { background: linear-gradient(135deg, #667eea, #764ba2); }
        .stat-icon.dosen { background: linear-gradient(135deg, #f093fb, #f5576c); }
        .stat-icon.matkul { background: linear-gradient(135deg, #4facfe, #00f2fe); }
        .stat-icon.jadwal { background: linear-gradient(135deg, #43e97b, #38f9d7); }
        
        .stat-number {
            font-size: 2.2rem;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #718096;
            font-weight: 500;
            margin-bottom: 0;
        }
        
        .stat-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 15px;
        }
        
        .stat-link:hover {
            gap: 10px;
            transition: 0.3s;
        }
        
        /* Menu Card Styling */
        .menu-card {
            background: white;
            border-radius: 24px;
            border: none;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            overflow: hidden;
        }
        
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .menu-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 15px;
            transition: 0.3s;
        }
        
        .menu-card:hover .menu-icon {
            transform: scale(1.1);
        }
        
        .menu-title {
            font-weight: 700;
            font-size: 1.2rem;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .menu-desc {
            font-size: 0.85rem;
            color: #718096;
        }
        
        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
            border-radius: 24px;
            padding: 2rem;
            color: #2d3748;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }
        
        .welcome-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .welcome-text {
            opacity: 0.8;
            margin-bottom: 0;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            padding: 2rem;
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
        }
        
        /* Animasi */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate {
            animation: fadeInUp 0.6s ease forwards;
        }
        
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-custom px-4 py-2 mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap me-2"></i> SIAKAD Universitas
            </a>
            <div class="d-flex align-items-center gap-3">
                <div class="user-name">
                    <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['username']); ?>
                </div>
                <a href="logout.php" class="btn btn-logout text-white" onclick="return confirm('Yakin ingin keluar?')">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        
        <!-- Welcome Section -->
        <div class="welcome-section animate">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="welcome-title">
                        <i class="fas fa-hand-peace me-2"></i> Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?>!
                    </h1>
                    <p class="welcome-text">
                        Kelola data akademik dengan mudah dan cepat. Pilih menu yang tersedia untuk memulai.
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <i class="fas fa-chalkboard-user fa-4x opacity-50"></i>
                </div>
            </div>
        </div>
        
        <!-- Statistik Cards -->
        <div class="row mb-5">
            <div class="col-md-3 mb-3 animate delay-1">
                <div class="stat-card" onclick="window.location.href='mahasiswa.php'">
                    <div class="card-body">
                        <div class="stat-icon mahasiswa">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="stat-number"><?= $count_mahasiswa ?></div>
                        <p class="stat-label">Total Mahasiswa</p>
                        <span class="stat-link">
                            Kelola <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3 animate delay-2">
                <div class="stat-card" onclick="window.location.href='dosen.php'">
                    <div class="card-body">
                        <div class="stat-icon dosen">
                            <i class="fas fa-chalkboard-user"></i>
                        </div>
                        <div class="stat-number"><?= $count_dosen ?></div>
                        <p class="stat-label">Total Dosen</p>
                        <span class="stat-link">
                            Kelola <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3 animate delay-3">
                <div class="stat-card" onclick="window.location.href='matkul.php'">
                    <div class="card-body">
                        <div class="stat-icon matkul">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-number"><?= $count_matkul ?></div>
                        <p class="stat-label">Total Mata Kuliah</p>
                        <span class="stat-link">
                            Kelola <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3 animate delay-4">
                <div class="stat-card" onclick="window.location.href='jadwal.php'">
                    <div class="card-body">
                        <div class="stat-icon jadwal">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="stat-number"><?= $count_jadwal ?></div>
                        <p class="stat-label">Total Jadwal</p>
                        <span class="stat-link">
                            Kelola <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Menu Cards -->
        <h3 class="text-white mb-4 animate">
            <i class="fas fa-th-large me-2"></i> Menu Aplikasi
        </h3>
        
        <div class="row">
            <div class="col-md-3 mb-4 animate delay-1">
                <a href="mahasiswa.php" class="menu-card text-decoration-none">
                    <div class="card-body text-center py-4">
                        <div class="menu-icon" style="background: linear-gradient(135deg, #667eea20, #764ba220); color: #667eea;">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h5 class="menu-title">Data Mahasiswa</h5>
                        <p class="menu-desc">Tambah, edit, hapus, dan lihat data mahasiswa</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-4 animate delay-2">
                <a href="dosen.php" class="menu-card text-decoration-none">
                    <div class="card-body text-center py-4">
                        <div class="menu-icon" style="background: linear-gradient(135deg, #f093fb20, #f5576c20); color: #f5576c;">
                            <i class="fas fa-chalkboard-user"></i>
                        </div>
                        <h5 class="menu-title">Data Dosen</h5>
                        <p class="menu-desc">Tambah, edit, hapus, dan lihat data dosen</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-4 animate delay-3">
                <a href="matkul.php" class="menu-card text-decoration-none">
                    <div class="card-body text-center py-4">
                        <div class="menu-icon" style="background: linear-gradient(135deg, #4facfe20, #00f2fe20); color: #00a3c4;">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h5 class="menu-title">Data Mata Kuliah</h5>
                        <p class="menu-desc">Tambah, edit, hapus, dan lihat data mata kuliah</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-4 animate delay-4">
                <a href="jadwal.php" class="menu-card text-decoration-none">
                    <div class="card-body text-center py-4">
                        <div class="menu-icon" style="background: linear-gradient(135deg, #43e97b20, #38f9d720); color: #38b2ac;">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                        <h5 class="menu-title">Data Jadwal</h5>
                        <p class="menu-desc">Tambah, edit, hapus, dan lihat data jadwal kuliah</p>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Informasi Sistem -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card bg-white bg-opacity-10 text-white border-0">
                    <div class="card-body py-3 text-center">
                        <small>
                            <i class="fas fa-database me-1"></i> Sistem Informasi Akademik | 
                            <i class="fas fa-code me-1 ms-2"></i> PHP Native + MySQL | 
                            <i class="fas fa-mobile-alt me-1 ms-2"></i> Responsive Design
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <div class="footer">
        <i class="fas fa-copyright me-1"></i> 2024 SIAKAD Universitas | Dibangun dengan <i class="fas fa-heart text-danger"></i> untuk Pendidikan
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>