@extends('layouts.started')
@section('started')
    <div class="p-4 space-y-4 bg-[#F8F8F8] min-h-screen font-semibold pt-16">
        <div class="text-2xl flex w-full justify-center text-[#048FBD] font-bold">Daftar Akun</div>
        <div class="text-xs">
            Nama Siswa
            <input type="text" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none">
        </div>
        <div class="text-xs">
            Nomor HP
            <input type="number" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none">
        </div>
        <div class="text-xs">
            Jenis Kelamin
            <select name="jk" id="" class="w-full h-8 pl-2 pr-4 border rounded-lg focus:outline-none">
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>
        <div class="text-xs">
            Pilihan Sekolah
            <select name="jk" id="" class="w-full h-8 pl-2 pr-4 border rounded-lg focus:outline-none">
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>
        <button class="w-full h-10 bg-[#51C2FF] rounded-lg text-white cursor-pointer">Daftar</button>
        <div class="flex text-xs justify-center">Sudah Punya Akun ? <a href="login" class="text-[#048FBD] pl-1 cursor-pointer"> Login</a></div>
    </div>
@endsection

