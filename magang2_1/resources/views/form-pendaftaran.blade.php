@extends('layouts.app')

@section('content')
    <div class="text-2xl flex w-full justify-center font-bold">Form Pendaftaran Siswa</div>
    <form id="form-pendaftaran" class="space-y-4">
        <div>Data Siswa</div>
        <div class="text-xs">
            NISN
            <input id="nisn" type="text" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight" placeholder="Masukkan NISN">
        </div>
        <div class="text-xs grid grid-cols-2 gap-4">
            <div>
                Tempat Lahir
                <input id="tempat_lahir" type="text" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight" placeholder="Tempat Lahir">
            </div>
            <div>
                Tanggal Lahir
                <input id="tanggal_lahir" type="date" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight">
            </div>
        </div>
        <div class="text-xs grid grid-cols-2 gap-4">
            <div>
                Jurusan 1
                <select id="jurusan1" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight">
                    <option value="1">IPA</option>
                    <option value="2">IPS</option>
                </select>
            </div>
            <div>
                Jurusan 2
                <select id="jurusan2" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight">
                    <option value="1">IPA</option>
                    <option value="2">IPS</option>
                </select>
            </div>
        </div>
        <div class="text-xs">
            Alamat
            <textarea id="alamat" cols="30" rows="5" class="w-full py-2 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight" placeholder="Masukkan Alamat Lengkap"></textarea>
        </div>
        <div>Data Orang Tua</div>
        <div class="text-xs">
            Nama Ayah
            <input type="text" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight" placeholder="Masukkan Nama Ayah">
        </div>
        <div class="text-xs">
            Nama Ibu
            <input type="text" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight" placeholder="Masukkan Nama Ibu">
        </div>
        <div class="text-xs">
            Penghasilan Ayah
            <input type="text" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight" placeholder="Masukkan Penghasilan Ayah">
        </div>
        <div class="text-xs">
            Penghasilan Ibu
            <input type="text" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight" placeholder="Masukkan Penghasilan Ibu">
        </div>
        <button type="submit" class="bg-[#51C2FF] text-white p-2 rounded-lg">Submit</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-pendaftaran');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Ambil token dari localStorage jika API memerlukan otentikasi
                const token = localStorage.getItem('token');

                // Kumpulkan data dari form
                const payload = {
                    nisn: document.getElementById('nisn').value,
                    tempat_lahir: document.getElementById('tempat_lahir').value,
                    tanggal_lahir: document.getElementById('tanggal_lahir').value,
                    alamat: document.getElementById('alamat').value,
                    jurusan1_id: parseInt(document.getElementById('jurusan1').value),
                    jurusan2_id: parseInt(document.getElementById('jurusan2').value)
                };

                try {
                    const response = await fetch(`http://127.0.0.1:8000/api/user/peserta/form-peserta`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            // Sertakan header Authorization jika diperlukan
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();
                    console.log('API Result:', result);

                    if (response.ok) {
                        alert('Data berhasil dikirim!');
                        // Redirect atau clear form jika diperlukan
                    } else {
                        alert('Gagal mengirim data: ' + (result.message || 'Terjadi kesalahan.'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengirim data.');
                }
            });
        });
    </script>
@endsection
