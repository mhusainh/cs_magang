@extends('layouts.app')

@section('content')
    <div class="text-xl flex w-full justify-center font-bold mb-4">Informasi Peserta</div>

    <div class="space-y-4">
        <div class="font-medium">Data Siswa</div>

        <!-- NISN -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>NISN</span>
            <span class="font-light text-xs" id="nisn"></span>
        </div>

        <!-- Tempat, Tanggal Lahir -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Tempat, Tanggal Lahir</span>
            <span class="font-light text-xs" id="tempat-tanggal-lahir"></span>
        </div>

        <!-- No Telepon -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>No Telepon</span>
            <span class="font-light text-xs" id="no-telp"></span>
        </div>

        <!-- Jenjang -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Jenjang</span>
            <span class="font-light text-xs" id="jenjang"></span>
        </div>

        <!-- Nama Ayah -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Nama Ayah</span>
            <span class="font-light text-xs" id="nama-ayah"></span>
        </div>

        <!-- Nama Ibu -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Nama Ibu</span>
            <span class="font-light text-xs" id="nama-ibu"></span>
        </div>

        <!-- Penghasilan Orang Tua -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Penghasilan Orang Tua</span>
            <span class="font-light text-xs" id="penghasilan-ayah"></span>
        </div>

        <!-- Alamat -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Alamat</span>
            <span class="font-light text-xs" id="alamat"></span>
        </div>
    </div>

    <div class="space-y-4">
        <div class="font-medium">Berkas</div>
        <!-- Contoh berkas statis, bisa diubah jadi dynamic juga -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Akte kelahiran</span>
            <span class="font-light text-xs">tex.png</span>
        </div>
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Kartu Keluarga</span>
            <span class="font-light text-xs">tex.png</span>
        </div>
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Pas Foto</span>
            <span class="font-light text-xs">tex.png</span>
        </div>
    </div>

    <button
        class="fixed bottom-20 right-4 w-24 flex justify-center text-sm bg-[#51C2FF] text-white p-2 rounded-lg shadow-lg z-50">
        Edit
    </button>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('token');
            if (!token) {
                console.error('Token tidak ditemukan. Silahkan login kembali.');
                window.location.href = '/login';
                return;
            }
            console.log('Token:', token);

            fetch('http://127.0.0.1:8000/api/user/peserta', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(result => {
                    console.log('API Result:', result);
                    if (result.message === 'Unauthenticated.') {
                        // Bisa tambahkan logika untuk redirect atau login ulang
                        console.error('Gagal autentikasi. Token mungkin tidak valid.');
                    } else {
                        // Proses data jika autentikasi berhasil
                        let data = result.data;
                        document.getElementById('nisn').textContent = data.nisn ?? '';
                        document.getElementById('tempat-tanggal-lahir').textContent = (data.tempat_lahir ??
                            '') + ', ' + (data.tanggal_lahir ?? '');
                        document.getElementById('no-telp').textContent = data.no_telp ?? '';
                        document.getElementById('jenjang').textContent = data.jenjang_sekolah ?? '';
                        document.getElementById('nama-ayah').textContent = (data.biodata_ortu && data
                            .biodata_ortu.nama_ayah) || '';
                        document.getElementById('nama-ibu').textContent = (data.biodata_ortu && data
                            .biodata_ortu.nama_ibu) || '';
                        document.getElementById('penghasilan-ayah').textContent = (data.biodata_ortu && data
                            .biodata_ortu.penghasilan_ayah) || '';
                        document.getElementById('alamat').textContent = data.alamat ?? '';
                    }
                })
                .catch(error => {
                    console.error('Terjadi kesalahan saat mengambil data peserta:', error);
                });
        });
    </script>
@endsection
