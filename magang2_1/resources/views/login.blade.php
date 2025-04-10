@extends('layouts.started')
@section('started')
    <div class="p-4 space-y-4 bg-[#F8F8F8] min-h-screen font-semibold pt-24">
        <div class="text-2xl flex w-full justify-center text-[#048FBD] font-bold">Login</div>
        <div class="text-xs">
            Nomor HP
            <input id="phoneInput" type="number" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none">
        </div>
        <button id="loginBtn" class="w-full h-10 bg-[#51C2FF] rounded-lg text-white cursor-pointer">Login</button>
        <div class="flex text-xs justify-center">
            Belum Punya Akun ? 
            <a href="register" class="text-[#048FBD] pl-1 cursor-pointer">Daftar segera</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginBtn = document.getElementById('loginBtn');
            const phoneInput = document.getElementById('phoneInput');

            loginBtn.addEventListener('click', async function() {
                const no_telp = phoneInput.value;

                // Pastikan nomor HP tidak kosong
                if (!no_telp) {
                    alert('Nomor HP wajib diisi!');
                    return;
                }

                try {
                    const response = await fetch('http://127.0.0.1:8000/api/auth/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            no_telp: no_telp
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Simpan token di local storage
                        localStorage.setItem('token', data.data.token);
                        // alert('Login berhasil!');
                        // Redirect jika perlu, misalnya:
                        window.location.href = '/';
                    } else {
                        // Menangani error dari API
                        alert('Login gagal: ' + (data.message || 'Periksa kembali nomor HP Anda.'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat melakukan login.');
                }
            });
        });
    </script>
@endsection
