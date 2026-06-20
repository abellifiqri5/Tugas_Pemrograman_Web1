// ==========================================
// CRUD MAHASISWA
// ==========================================
function loadData() {
    fetch('api.php?action=list')
        .then(response => response.json())
        .then(data => {
            let html = '';
            if (data.length === 0) {
                html = `<tr><td colspan="6" class="text-center text-muted p-4">Belum ada data mahasiswa. Silakan tambah data baru.</td></tr>`;
            } else {
                data.forEach((mhs, index) => {
                    html += `
                        <tr>
                            <td class="ps-3 align-middle">${index + 1}</td>
                            <td class="align-middle"><span class="badge bg-secondary">${escapeHtml(mhs.nim)}</span></td>
                            <td class="align-middle"><strong>${escapeHtml(mhs.nama)}</strong></td>
                            <td class="align-middle">${escapeHtml(mhs.jurusan)}</td>
                            <td class="align-middle">${escapeHtml(mhs.email)}</td>
                            <td class="text-center align-middle">
                                <button class="btn btn-warning btn-sm me-1" onclick="siapkanEdit(${mhs.id})" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                    </svg>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="hapusData(${mhs.id})" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3h11V2h-11v1z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
            const tbody = document.getElementById('tempat-data-mahasiswa');
            if (tbody) {
                tbody.innerHTML = html;
            }
        })
        .catch(err => {
            console.error("Gagal memuat data: ", err);
            const tbody = document.getElementById('tempat-data-mahasiswa');
            if (tbody) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger p-4">Gagal memuat data. Pastikan api.php berjalan dengan benar.</td></tr>`;
            }
        });
}

function siapkanTambah() {
    document.getElementById('modalTitle').innerText = 'Tambah Data Mahasiswa';
    document.getElementById('formMahasiswa').reset();
    document.getElementById('mahasiswa_id').value = '';
    // Reset validasi
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
}

function siapkanEdit(id) {
    document.getElementById('modalTitle').innerText = 'Ubah Data Mahasiswa';
    document.getElementById('formMahasiswa').reset();
    
    fetch(`api.php?action=get_single&id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('mahasiswa_id').value = data.id;
            document.getElementById('nim').value = data.nim;
            document.getElementById('nama').value = data.nama;
            document.getElementById('jurusan').value = data.jurusan;
            document.getElementById('email').value = data.email;
            
            const modal = new bootstrap.Modal(document.getElementById('mahasiswaModal'));
            modal.show();
        })
        .catch(err => console.error("Gagal mengambil data detail: ", err));
}

function simpanData(event) {
    event.preventDefault();
    
    const form = document.getElementById('formMahasiswa');
    const formData = new FormData(form);
    
    // Nonaktifkan tombol submit sementara
    const btnSimpan = document.getElementById('btnSimpan');
    btnSimpan.disabled = true;
    btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
    
    fetch('api.php?action=save', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(res => {
        if (res.status === 'success') {
            alert('Data berhasil disimpan!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('mahasiswaModal'));
            modal.hide();
            loadData();
        } else {
            alert('Error: ' + (res.message || 'Terjadi kesalahan'));
        }
    })
    .catch(err => {
        console.error("Gagal mengirim data: ", err);
        alert('Gagal menyimpan data. Periksa koneksi atau file api.php');
    })
    .finally(() => {
        btnSimpan.disabled = false;
        btnSimpan.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save me-1" viewBox="0 0 16 16"><path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1H2z"/></svg> Simpan';
    });
}

function hapusData(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini secara permanen?')) {
        const formData = new FormData();
        formData.append('id', id);
        
        fetch('api.php?action=delete', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(res => {
            if (res.status === 'success') {
                alert('Data berhasil dihapus!');
                loadData();
            } else {
                alert('Error: ' + (res.message || 'Gagal menghapus data'));
            }
        })
        .catch(err => console.error("Gagal menghapus data: ", err));
    }
}

// Fungsi bantuan untuk menghindari XSS
function escapeHtml(str) {
    if (!str) return '';
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}
// ==================== DOSEN ====================
function loadDosen() {
    fetch('api.php?action=list_dosen')
        .then(response => response.json())
        .then(data => {
            let html = '';
            data.forEach((item, index) => {
                html += `<tr>
                    <td class="ps-3">${index+1}</td>
                    <td>${item.nidn}</td>
                    <td>${item.nama}</td>
                    <td>${item.alamat}</td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm me-1" onclick="siapkanEditDosen(${item.id})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="hapusDosen(${item.id})">Hapus</button>
                    </td>
                </tr>`;
            });
            document.getElementById('tempat-data-dosen').innerHTML = html;
        });
}

function simpanDosen(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('formDosen'));
    
    fetch('api.php?action=save_dosen', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(res => {
        if (res.status === 'success') {
            alert('Data dosen berhasil disimpan!');
            bootstrap.Modal.getInstance(document.getElementById('dosenModal')).hide();
            loadDosen();
        } else {
            alert('Error: ' + res.message);
        }
    });
}

function siapkanEditDosen(id) {
    fetch(`api.php?action=get_dosen&id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('dosen_id').value = data.id;
            document.getElementById('nidn').value = data.nidn;
            document.getElementById('nama_dosen').value = data.nama;
            document.getElementById('alamat').value = data.alamat;
            document.getElementById('modalTitleDosen').innerText = 'Edit Dosen';
            new bootstrap.Modal(document.getElementById('dosenModal')).show();
        });
}

function hapusDosen(id) {
    if (confirm('Yakin ingin menghapus dosen ini?')) {
        const formData = new FormData();
        formData.append('id', id);
        
        fetch('api.php?action=delete_dosen', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(res => {
            if (res.status === 'success') {
                alert('Data berhasil dihapus!');
                loadDosen();
            } else {
                alert('Error: ' + res.message);
            }
        });
    }
}

function siapkanTambahDosen() {
    document.getElementById('formDosen').reset();
    document.getElementById('dosen_id').value = '';
    document.getElementById('modalTitleDosen').innerText = 'Tambah Dosen';
}
// ==================== MATKUL ====================
function loadMatkul() {
    fetch('api.php?action=list_matkul')
        .then(res => res.json())
        .then(data => {
            let html = '';
            data.forEach((item, i) => {
                html += `<tr><td>${i+1}</td><td>${item.kode_matkul}</td><td>${item.nama_matkul}</td><td>${item.sks}</td>
                <td><button class="btn btn-warning btn-sm" onclick="siapkanEditMatkul(${item.id})">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="hapusMatkul(${item.id})">Hapus</button></td></tr>`;
            });
            document.getElementById('tempat-data-matkul') && (document.getElementById('tempat-data-matkul').innerHTML = html);
        });
}
function siapkanTambahMatkul() {
    document.getElementById('modalTitleMatkul').innerText = 'Tambah Matkul';
    document.getElementById('formMatkul').reset();
    document.getElementById('matkul_id').value = '';
    new bootstrap.Modal(document.getElementById('matkulModal')).show();
}
function siapkanEditMatkul(id) {
    fetch(`api.php?action=get_matkul&id=${id}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('matkul_id').value = data.id;
            document.getElementById('kode_matkul').value = data.kode_matkul;
            document.getElementById('nama_matkul').value = data.nama_matkul;
            document.getElementById('sks').value = data.sks;
            new bootstrap.Modal(document.getElementById('matkulModal')).show();
        });
}
function simpanMatkul(event) {
    event.preventDefault();
    const fd = new FormData(document.getElementById('formMatkul'));
    fetch('api.php?action=save_matkul', { method: 'POST', body: fd })
        .then(() => location.reload());
}
function hapusMatkul(id) {
    if(confirm('Yakin?')){ const fd=new FormData(); fd.append('id',id); fetch('api.php?action=delete_matkul',{method:'POST',body:fd}).then(()=>loadMatkul());}
}

// ==================== JADWAL ====================
function loadJadwal() {
    fetch('api.php?action=list_jadwal')
        .then(res => res.json())
        .then(data => {
            let html = '';
            data.forEach((item, i) => {
                html += `<tr><td>${i+1}</td><td>${item.nama_dosen}</td><td>${item.nama_matkul}</td><td>${item.waktu}</td><td>${item.ruang}</td>
                <td><button class="btn btn-warning btn-sm" onclick="siapkanEditJadwal(${item.id})">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="hapusJadwal(${item.id})">Hapus</button></td></tr>`;
            });
            document.getElementById('tempat-data-jadwal') && (document.getElementById('tempat-data-jadwal').innerHTML = html);
        });
}
function loadDosenList() {
    fetch('api.php?action=get_dosen_list')
        .then(res => res.json())
        .then(data => {
            let opts = '<option value="">Pilih Dosen</option>';
            data.forEach(d => opts += `<option value="${d.id}">${d.nama}</option>`);
            document.getElementById('id_dosen') && (document.getElementById('id_dosen').innerHTML = opts);
        });
}
function loadMatkulList() {
    fetch('api.php?action=get_matkul_list')
        .then(res => res.json())
        .then(data => {
            let opts = '<option value="">Pilih Mata Kuliah</option>';
            data.forEach(m => opts += `<option value="${m.id}">${m.nama_matkul}</option>`);
            document.getElementById('id_matkul') && (document.getElementById('id_matkul').innerHTML = opts);
        });
}
function siapkanTambahJadwal() {
    document.getElementById('modalTitleJadwal').innerText = 'Tambah Jadwal';
    document.getElementById('formJadwal').reset();
    document.getElementById('jadwal_id').value = '';
    loadDosenList(); loadMatkulList();
    new bootstrap.Modal(document.getElementById('jadwalModal')).show();
}
function siapkanEditJadwal(id) {
    loadDosenList(); loadMatkulList();
    fetch(`api.php?action=get_jadwal&id=${id}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('jadwal_id').value = data.id;
            document.getElementById('id_dosen').value = data.id_dosen;
            document.getElementById('id_matkul').value = data.id_matkul;
            document.getElementById('waktu').value = data.waktu.replace(' ', 'T');
            document.getElementById('ruang').value = data.ruang;
            new bootstrap.Modal(document.getElementById('jadwalModal')).show();
        });
}
function simpanJadwal(event) {
    event.preventDefault();
    const fd = new FormData(document.getElementById('formJadwal'));
    fetch('api.php?action=save_jadwal', { method: 'POST', body: fd })
        .then(() => location.reload());
}
function hapusJadwal(id) {
    if(confirm('Yakin hapus jadwal?')){
        const fd = new FormData(); fd.append('id', id);
        fetch('api.php?action=delete_jadwal', { method: 'POST', body: fd })
            .then(() => loadJadwal());
    }
}

// Panggil fungsi sesuai halaman
if(document.getElementById('tempat-data-dosen')) loadDosen();
if(document.getElementById('tempat-data-matkul')) loadMatkul();
if(document.getElementById('tempat-data-jadwal')) loadJadwal();