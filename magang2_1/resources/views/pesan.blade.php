@extends('layouts.app')

@section('content')
    <div class="text-xl flex w-full justify-center font-bold">Pesan</div>
    <div class="w-full font-thin text-xs">
        @foreach($datapesan as $pesan)
        <span class="w-full flex text-[12px] pl-2 pr-2">{{ $pesan['tanggal'] }}</span>
        <div class="flex justify-between p-2 border-b border-gray-400">
            <div class="flex flex-col">
                <span class="font-bold text-sm">{{ $pesan['judul'] }}</span>
                <span class="font-medium">{{ $pesan['deskripsi'] }}</span>
            </div>
            <div class="flex flex-col justify-center">
                <img src="{{ asset('assets/svg/notif-message.svg') }}" alt="">
            </div>
        </div>
        @endforeach
    </div>
@endsection
