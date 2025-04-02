@extends('layouts.started')
@section('started')
    <div class="p-4 space-y-4 bg-[#F8F8F8] min-h-screen font-semibold pt-24">
        <div class="text-2xl flex w-full justify-center text-[#048FBD] font-bold">Login</div>
        <div class="text-xs">
            Nomor HP
            <input type="number" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none">
        </div>
        <button class="w-full h-10 bg-[#51C2FF] rounded-lg text-white cursor-pointer">Login</button>
        <div class="flex text-xs justify-center">Belum Punya Akun ? <a href="register" class="text-[#048FBD] pl-1 cursor-pointer">
                Daftar segera</a></div>
    </div>
@endsection

