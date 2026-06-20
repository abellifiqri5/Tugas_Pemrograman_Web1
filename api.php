<?php
session_start();
header('Content-Type: application/json');

// Proteksi API
if (!isset($_SESSION['login'])) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ilegal. Silakan login.']);
    exit;
}

include 'koneksi.php';

$action = $_GET['action'] ?? '';

// ==========================================
// CRUD MAHASISWA (yang sudah ada)
// ==========================================
if ($action == 'list') {
    $query = mysqli_query($conn, "SELECT * FROM mahasiswa ORDER BY id DESC");
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit;
}

if ($action == 'get_single') {
    $id = intval($_GET['id']);
    $query = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id = $id");
    echo json_encode(mysqli_fetch_assoc($query));
    exit;
}

if ($action == 'save') {
    $id = $_POST['id'] ?? '';
    $nim = mysqli_real_escape_string($conn, $_POST['nim']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    if (empty($id)) {
        $sql = "INSERT INTO mahasiswa (nim, nama, jurusan, email) VALUES ('$nim', '$nama', '$jurusan', '$email')";
    } else {
        $sql = "UPDATE mahasiswa SET nim='$nim', nama='$nama', jurusan='$jurusan', email='$email' WHERE id=$id";
    }
    
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
    exit;
}

if ($action == 'delete') {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM mahasiswa WHERE id = $id";
    echo json_encode(['status' => mysqli_query($conn, $sql) ? 'success' : 'error']);
    exit;
}

// ==========================================
// CRUD DOSEN
// ==========================================
if ($action == 'list_dosen') {
    $query = mysqli_query($conn, "SELECT * FROM dosen ORDER BY id DESC");
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit;
}

if ($action == 'get_dosen') {
    $id = intval($_GET['id']);
    $query = mysqli_query($conn, "SELECT * FROM dosen WHERE id = $id");
    echo json_encode(mysqli_fetch_assoc($query));
    exit;
}

if ($action == 'save_dosen') {
    $id = $_POST['id'] ?? '';
    $nidn = mysqli_real_escape_string($conn, $_POST['nidn']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    
    if (empty($id)) {
        $sql = "INSERT INTO dosen (nidn, nama, alamat) VALUES ('$nidn', '$nama', '$alamat')";
    } else {
        $sql = "UPDATE dosen SET nidn='$nidn', nama='$nama', alamat='$alamat' WHERE id=$id";
    }
    
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
    exit;
}

if ($action == 'delete_dosen') {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM dosen WHERE id = $id";
    echo json_encode(['status' => mysqli_query($conn, $sql) ? 'success' : 'error']);
    exit;
}

// ==========================================
// CRUD MATA KULIAH
// ==========================================
if ($action == 'list_matkul') {
    $query = mysqli_query($conn, "SELECT * FROM matkul ORDER BY id DESC");
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit;
}

if ($action == 'get_matkul') {
    $id = intval($_GET['id']);
    $query = mysqli_query($conn, "SELECT * FROM matkul WHERE id = $id");
    echo json_encode(mysqli_fetch_assoc($query));
    exit;
}

if ($action == 'save_matkul') {
    $id = $_POST['id'] ?? '';
    $kode_matkul = mysqli_real_escape_string($conn, $_POST['kode_matkul']);
    $nama_matkul = mysqli_real_escape_string($conn, $_POST['nama_matkul']);
    $sks = intval($_POST['sks']);
    
    if (empty($id)) {
        $sql = "INSERT INTO matkul (kode_matkul, nama_matkul, sks) VALUES ('$kode_matkul', '$nama_matkul', $sks)";
    } else {
        $sql = "UPDATE matkul SET kode_matkul='$kode_matkul', nama_matkul='$nama_matkul', sks=$sks WHERE id=$id";
    }
    
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
    exit;
}

if ($action == 'delete_matkul') {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM matkul WHERE id = $id";
    echo json_encode(['status' => mysqli_query($conn, $sql) ? 'success' : 'error']);
    exit;
}

// ==========================================
// CRUD JADWAL
// ==========================================
if ($action == 'list_jadwal') {
    $query = mysqli_query($conn, "SELECT jadwal.*, dosen.nama as nama_dosen, matkul.nama_matkul 
                                  FROM jadwal 
                                  JOIN dosen ON jadwal.id_dosen = dosen.id 
                                  JOIN matkul ON jadwal.id_matkul = matkul.id 
                                  ORDER BY jadwal.id DESC");
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit;
}

if ($action == 'get_jadwal') {
    $id = intval($_GET['id']);
    $query = mysqli_query($conn, "SELECT * FROM jadwal WHERE id = $id");
    echo json_encode(mysqli_fetch_assoc($query));
    exit;
}

if ($action == 'save_jadwal') {
    $id = $_POST['id'] ?? '';
    $id_dosen = intval($_POST['id_dosen']);
    $id_matkul = intval($_POST['id_matkul']);
    $waktu = mysqli_real_escape_string($conn, $_POST['waktu']);
    $ruang = mysqli_real_escape_string($conn, $_POST['ruang']);
    
    if (empty($id)) {
        $sql = "INSERT INTO jadwal (id_dosen, id_matkul, waktu, ruang) VALUES ($id_dosen, $id_matkul, '$waktu', '$ruang')";
    } else {
        $sql = "UPDATE jadwal SET id_dosen=$id_dosen, id_matkul=$id_matkul, waktu='$waktu', ruang='$ruang' WHERE id=$id";
    }
    
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
    exit;
}

if ($action == 'delete_jadwal') {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM jadwal WHERE id = $id";
    echo json_encode(['status' => mysqli_query($conn, $sql) ? 'success' : 'error']);
    exit;
}

if ($action == 'get_dosen_list') {
    $query = mysqli_query($conn, "SELECT id, nama FROM dosen");
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit;
}

if ($action == 'get_matkul_list') {
    $query = mysqli_query($conn, "SELECT id, nama_matkul FROM matkul");
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit;
}

// Jika action tidak ditemukan
echo json_encode(['status' => 'error', 'message' => 'Action tidak ditemukan']);
exit;
?>