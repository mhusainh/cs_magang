@extends('layouts.app')

@section('content')
    <div class="bg-white rounded-xl shadow-sm">
        <img class="w-full" src="{{ asset('assets/svg/kartu-akun.svg') }}" alt="">
    </div>
    <div class="text-xs font-semibold">
        <div class="pb-4 py-8">Akun</div>
        <div class="flex font-normal border-b-2 py-2 border-gray-400">
            <img src="{{ asset('assets/svg/User.svg') }}" alt="">
            <span class="pl-2">Informasi Akun</span>
        </div>
        <div class="flex font-normal border-b-2 py-2 border-gray-400">
            <img src="{{ asset('assets/svg/Lock.svg') }}" alt="">
            <span class="pl-2">Keamanan Akun</span>
        </div>
        <div class="flex font-normal border-b-2 py-2 border-gray-400">
            <img src="{{ asset('assets/svg/Logout.svg') }}" alt="">
            <span class="pl-2">Logout</span>
        </div>
    </div>
@endsection
