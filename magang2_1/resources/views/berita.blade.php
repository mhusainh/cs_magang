@extends('layouts.app')

@section('content')
    <img class="w-full" src="{{ asset('assets/svg/card-example.svg') }}" alt="">


    <div class="flex w-full max-w-sm justify-center">
        <div class="grid grid-cols-4 py-4 gap-8 ">
            <a href="{{ route('home') }}"
                class="text-center @if (request()->routeIs('home')) text-ppdb-green @else text-gray-500 @endif">
                <div class=" flex flex-col items-center ">
                    <img src="{{ asset('assets/svg/Icon Data Siswa.svg') }}" alt="Home">

                </div>
            </a>
            <a href="#" class="text-center text-gray-500">
                <div class="flex flex-col items-center">
                    <img src="{{ asset('assets/svg/Icon Peringkat.svg') }}" alt="Jadwal">

                </div>
            </a>
            <a href="#" class="text-center text-gray-500">
                <div class="flex flex-col items-center">
                    <img src="{{ asset('assets/svg/Icon Riwayat.svg') }}" alt="Berita">

                </div>
            </a>

        </div>
    </div>
    <div class="flex w-full max-w-sm justify-center">
        <img class="w-full" src="{{ asset('assets/svg/Progress.svg') }}" alt="Home">
    </div>
    <div class="text-xs font-semibold ">
        <div class="pb-4">Unggah Berkas</div>
        <!--- Klik Untuk Unggah Berkas --->
        <img class="w-full" src="{{ asset('assets/svg/card-unggah-berkas.svg') }}" alt="">
    </div>
@endsection
