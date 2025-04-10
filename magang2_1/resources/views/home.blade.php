@extends('layouts.app')

@section('content')
<div class=" rounded-xl overflow-hidden text-white font-sans" style="background-image: url('{{ asset('assets/svg/background_card_name.svg') }}'); background-size: cover; background-position: center;">
    <div class="flex p-4 " >
      <div class="w-1/3">
        <img class="w-full rounded-md" src="{{ asset('assets/img/profile_default.png') }}" alt="Profile">
      </div>
      <div class="flex flex-col justify-center w-3/3 pl-2 ">
        <p class="font-semibold text-sm">Husain</p>
        <p class="text-xs">326032516540</p>
        <p class="font-bold text-xs">Siswa</p>
      </div>
    </div>
    <div class="text-xs px-4 pb-2 font-normal">
      <div class="flex justify-between">
        <span>Jenis Kelamin : Laki - laki</span>
        <span>Kelas : 12</span>
        <span>Program : IPA</span>
      </div>
    </div>
    <div class="bg-transparent text-white text-sm font-bold px-4 pb-4 pt-2">
      SMK Walisongo Semarang
    </div>
  </div>
  
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
            <a href="{{ route('pesan') }}" class="text-center ">
                <div class="flex flex-col items-center">
                    <img src="{{ asset('assets/svg/Icon Pesan.svg') }}" alt="Account">
                </div>
            </a>
        </div>
    </div>
    <div class="flex w-full max-w-sm items-center justify-center  ">
        <!-- Step 1 - Isi Form -->
        <div class="flex flex-col items-center gap-1 text-center    ">
            <img src="{{ asset('assets/svg/done-progress-icon.svg') }}" alt="Done">
            <span class="text-[10px] font-semibold ">Isi Form</span>
        </div>

        <img src="{{ asset('assets/svg/Line-done.svg') }}" alt="Line" class="self-center">

        <!-- Step 2 - Unggah Berkas -->
        <div class="flex flex-col items-center gap-1 text-center">
            <img src="{{ asset('assets/svg/Now Progress 2.svg') }}" alt="Current">
            <span class="text-[10px] font-semibold ">Unggah Berkas</span>
        </div>

        <img src="{{ asset('assets/svg/Line-undone.svg') }}" alt="Line" class="self-center">

        <!-- Step 3 - Pengajuan Biaya -->
        <div class="flex flex-col items-center gap-1 text-center">
            <img src="{{ asset('assets/svg/before-progress-icon-3.svg') }}" alt="Undone">
            <span class="text-[10px] ">Pengajuan Biaya</span>
        </div>
    </div>
    <a href="{{ route('unggah-berkas') }}">
        <div class="text-xs font-semibold ">
            <div class="pb-4">Unggah Berkas</div>
            <div class="w-full bg-[#0267B2] p-2 rounded-lg min-h-12 flex">
                <img src="{{ asset('assets/svg/Upload To FTP.svg') }}" alt="">
                <div class="flex flex-col justify-center pl-2 text-white">
                    <span>Unggah Berkas</span>
                    <span class="text-[10px] font-light">Upload Dokumen Pendukung</span>
                </div>
            </div>
    </a>
    {{-- <!--- Klik Untuk Unggah Berkas --->
            <a href="{{ route('unggah-berkas') }}"><img class="w-full" src="{{ asset('assets/svg/Isi Form.svg') }}" alt=""></a> --}}
    </div>
@endsection
