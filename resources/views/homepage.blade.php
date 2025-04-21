@extends('layouts.started')
@section('started')

    @php
        $beritaImages = [
            'assets/img/berita.png',
            'assets/img/berita.png',
            // kamu bisa tambahkan lebih banyak gambar di sini
        ];
    @endphp

    @foreach($beritaImages as $img)
        <img src="{{ asset($img) }}" alt="Berita" class="w-full">
    @endforeach

    <div class="w-full flex justify-center">
        <button id="loginBtn" class="w-32 h-10 bg-[#51C2FF] rounded-lg text-white cursor-pointer fixed bottom-8" >Login</button>
    </div>
<script>
    const loginBtn = document.getElementById('loginBtn');
    loginBtn.addEventListener('click', () => {
        window.location.href = '/login';
    })
</script>
@endsection

