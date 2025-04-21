@extends('layouts.app')

@section('content')
    <div class="text-xl flex w-full justify-center font-bold mb-4">Informasi Peserta</div>

    <!-- Tampilan Informasi -->
    <div id="info-display" class="space-y-4">
        <div class="font-medium">Data Siswa</div>
        <!-- NISN -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>NISN</span>
            <span class="font-light text-xs" id="nisn"></span>
        </div>
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Nama</span>
            <span class="font-light text-xs" id="nama"></span>
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
        <!-- Jenis Kelamin -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Jenis Kelamin</span>
            <span class="font-light text-xs" id="jenis_kelamin"></span>
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

    <!-- Form Edit -->
    <div id="edit-form" class="hidden space-y-4">
        <div class="font-medium">Edit Data Siswa</div>

        <div class="flex flex-col">
            <label for="edit-nisn">NISN</label>
            <input id="edit-nisn" type="text" class="border rounded p-1 text-xs">
        </div>
        <div class="flex flex-col">
            <label for="edit-nama">Nama</label>
            <input id="edit-nama" type="text" class="border rounded p-1 text-xs">
        </div>
        <div class="flex flex-col">
            <label for="edit-tempat-lahir">Tempat Lahir</label>
            <input id="edit-tempat-lahir" type="text" class="border rounded p-1 text-xs">
        </div>
        <div class="flex flex-col">
            <label for="edit-tanggal-lahir">Tanggal Lahir</label>
            <input id="edit-tanggal-lahir" type="date" class="border rounded p-1 text-xs">
        </div>
        <div class="flex flex-col">
            <label for="edit-no-telp">No Telepon</label>
            <input id="edit-no-telp" type="text" class="border rounded p-1 text-xs">
        </div>
        <div class="flex flex-col">
            <label for="edit-jenis_kelamin">Jenis Kelamin</label>
            <input id="edit-jenis_kelamin" type="text" class="border rounded p-1 text-xs">
        </div>
        <div class="flex flex-col">
            <label for="edit-jenjang">Jenjang</label>
            <input id="edit-jenjang" type="text" class="border rounded p-1 text-xs">
        </div>
        <div class="flex flex-col">
            <label for="edit-nama-ayah">Nama Ayah</label>
            <input id="edit-nama-ayah" type="text" class="border rounded p-1 text-xs">
        </div>
        <div class="flex flex-col">
            <label for="edit-nama-ibu">Nama Ibu</label>
            <input id="edit-nama-ibu" type="text" class="border rounded p-1 text-xs">
        </div>
        <div class="flex flex-col">
            <label for="edit-penghasilan-ayah">Penghasilan Ayah</label>
            <input id="edit-penghasilan-ayah" type="text" class="border rounded p-1 text-xs">
        </div>
        <div class="flex flex-col">
            <label for="edit-alamat">Alamat</label>
            <textarea id="edit-alamat" class="border rounded p-1 text-xs"></textarea>
        </div>

        <button id="simpan-btn" class="bg-green-500 text-white py-1 px-3 rounded">Simpan</button>
    </div>

    <!-- Tombol Edit -->
    <button id="edit-btn"
        class="fixed bottom-20 right-4 w-24 flex justify-center text-sm bg-[#51C2FF] text-white p-2 rounded-lg shadow-lg z-50">
        Edit
    </button>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const infoDisplay = document.getElementById('info-display');
            const editForm = document.getElementById('edit-form');
            const editBtn = document.getElementById('edit-btn');
            const simpanBtn = document.getElementById('simpan-btn');

            let pesertaData = {}; 
            AwaitFetchApi('user/peserta', 'GET', null)
                .then(result => {
                    // if (result.message === 'Unauthenticated.') {
                    //     alert('Sesi Anda telah habis. Silakan login kembali.');
                    //     return;
                    // }

                    const data = result.data;
                    pesertaData = data;

                    document.getElementById('nisn').textContent = data.nisn ?? '';
                    document.getElementById('nama').textContent = data.nama ?? '';
                    document.getElementById('tempat-tanggal-lahir').textContent =
                        `${data.tempat_lahir ?? ''}, ${data.tanggal_lahir ?? ''}`;
                    document.getElementById('no-telp').textContent = data.no_telp ?? '';
                    document.getElementById('jenis_kelamin').textContent = data.jenis_kelamin ?? '';
                    document.getElementById('jenjang').textContent = data.jenjang_sekolah ?? '';
                    document.getElementById('nama-ayah').textContent = data.biodata_ortu?.nama_ayah ?? '';
                    document.getElementById('nama-ibu').textContent = data.biodata_ortu?.nama_ibu ?? '';
                    document.getElementById('penghasilan-ayah').textContent = data.biodata_ortu
                        ?.penghasilan_ayah ?? '';
                    document.getElementById('alamat').textContent = data.alamat ?? '';
                });

            editBtn.addEventListener('click', () => {
                infoDisplay.classList.add('hidden');
                editForm.classList.remove('hidden');

                document.getElementById('edit-nisn').value = pesertaData.nisn ?? '';
                document.getElementById('edit-nama').value = pesertaData.nama ?? '';
                document.getElementById('edit-tempat-lahir').value = pesertaData.tempat_lahir ?? '';
                document.getElementById('edit-tanggal-lahir').value = pesertaData.tanggal_lahir ?? '';
                document.getElementById('edit-no-telp').value = pesertaData.no_telp ?? '';
                document.getElementById('edit-jenis_kelamin').value = pesertaData.jenis_kelamin ?? '';
                document.getElementById('edit-jenjang').value = pesertaData.jenjang_sekolah ?? '';
                document.getElementById('edit-nama-ayah').value = pesertaData.biodata_ortu?.nama_ayah ?? '';
                document.getElementById('edit-nama-ibu').value = pesertaData.biodata_ortu?.nama_ibu ?? '';
                document.getElementById('edit-penghasilan-ayah').value = pesertaData.biodata_ortu
                    ?.penghasilan_ayah ?? '';
                document.getElementById('edit-alamat').value = pesertaData.alamat ?? '';
            });

            simpanBtn.addEventListener('click', () => {
                const dataBaru = {
                    nisn: document.getElementById('edit-nisn').value,
                    nama: document.getElementById('edit-nama').value,
                    tempat_lahir: document.getElementById('edit-tempat-lahir').value,
                    tanggal_lahir: document.getElementById('edit-tanggal-lahir').value,
                    no_telp: document.getElementById('edit-no-telp').value,
                    jenis_kelamin: document.getElementById('edit-jenis_kelamin').value,
                    jenjang_sekolah: document.getElementById('edit-jenjang').value,
                    alamat: document.getElementById('edit-alamat').value,
                    biodata_ortu: {
                        nama_ayah: document.getElementById('edit-nama-ayah').value,
                        nama_ibu: document.getElementById('edit-nama-ibu').value,
                        penghasilan_ayah: document.getElementById('edit-penghasilan-ayah').value,
                    }
                };

                AwaitFetchApi('user/peserta', 'PUT', dataBaru)
                    .then(result => {
                        alert('Data berhasil diperbarui!');
                        location.reload();
                    })
                    .catch(err => {
                        console.error('Gagal update:', err);
                        alert('Gagal memperbarui data.');
                    });
            });
        });
    </script>
@endsection
