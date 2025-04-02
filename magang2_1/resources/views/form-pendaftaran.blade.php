@extends('layouts.app')

@section('content')
    <div class="text-2xl flex w-full justify-center font-bold">Form Pendaftaran Siswa</div>
    <form action="" class="space-y-4">
        <div>Data Siswa</div>
        <div class="text-xs">
            NISN
            <input type="text" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight" placeholder="Masukkan NISN">
        </div>
        <div class="text-xs grid grid-cols-2 gap-4">
            <div>
                Tempat Lahir
                <input type="text" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight" placeholder="Tempat Lahir">
            </div>
            <div>
                Tanggal Lahir
                <input type="date" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight" >
            </div>
        </div>
        <div class="text-xs grid grid-cols-2 gap-4">
            <div>
                Jurusan 1
                <select name="jurusan" id="" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight">
                    <option value="IPA">IPA</option>
                    <option value="IPS">IPS</option>
                </select>
            </div>
            <div>
                Jurusan 2
                <select name="jurusan" id="" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight">
                    <option value="IPA">IPA</option>
                    <option value="IPS">IPS</option>
                </select>
            </div>
        </div>
        <div class="text-xs">
            Alamat
            <textarea name="" id="" cols="30" rows="5" class="w-full py-2 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight" placeholder="Masukkan Alamat Lengkap"></textarea>
            {{-- <input type="textarea" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none"> --}}
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
    </form>
@endsection
