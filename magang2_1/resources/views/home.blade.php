@extends('layouts.app')

@section('content')
        <img class="w-full" src="{{ asset('assets/svg/card-example.svg') }}" alt="">
        <div class="flex w-full max-w-sm justify-center">
            <div class="grid grid-cols-4 py-4 gap-8 ">
                <a href="{{ route('informasi-peserta') }}"
                    class="text-center @if (request()->routeIs('informasi-peserta')) text-ppdb-green @else text-gray-500 @endif">
                    <div class=" flex flex-col items-center ">
                        <img src="{{ asset('assets/svg/Icon Data Siswa.svg') }}" alt="data siswa">
                       
                    </div>
                </a>
                <a href="#" class="text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <img src="{{ asset('assets/svg/Icon Peringkat.svg') }}" alt="Jadwal">
                       
                    </div>
                </a>
                <a href="{{ route('riwayat') }}" class="text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <img src="{{ asset('assets/svg/Icon Riwayat.svg') }}" alt="Berita">
                      
                    </div>
                </a>
                <a href="{{ route('pesan') }}"
                    class="text-center ">
                    <div class="flex flex-col items-center">
                        <img src="{{ asset('assets/svg/Icon Pesan.svg') }}" alt="Account">
                    </div>
                </a>
            </div>
        </div>
        <div class="flex w-full max-w-sm justify-center">
            <img class="w-full" src="{{ asset('assets/svg/Progress.svg') }}" alt="Home">
        </div>
        <div class="text-xs font-semibold ">
            <div class="pb-4">Unggah Berkas</div>
            <div class="w-full bg-[#0267B2] rounded-lg min-h-12">

            </div>
            {{-- <!--- Klik Untuk Unggah Berkas --->
            <a href="{{ route('unggah-berkas') }}"><img class="w-full" src="{{ asset('assets/svg/Isi Form.svg') }}" alt=""></a> --}}
        </div>
@endsection
