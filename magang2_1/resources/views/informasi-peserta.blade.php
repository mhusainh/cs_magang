@extends('layouts.app')

@section('content')
    <div class="text-xl flex w-full justify-center font-bold mb-4">Informasi Peserta</div>
    
    <div class="space-y-4">
        <div class="font-medium">Data Siswa</div>
        
        @foreach([
            ['label' => 'NISN', 'value' => $data_siswa['nisn']],
            ['label' => 'Tempat, Tanggal lahir', 'value' => $data_siswa['tempat_lahir'].', '.$data_siswa['tanggal_lahir']],
            ['label' => 'No Telepon', 'value' => $data_siswa['no_telp']],
            ['label' => 'Jenjang', 'value' => $data_siswa['jenjang_sekolah']],
            ['label' => 'Nama Ayah', 'value' => $data_siswa['nama_ayah']],
            ['label' => 'Nama Ibu', 'value' => $data_siswa['nama_ibu']],
            ['label' => 'Penghasilan Orang Tua', 'value' => $data_siswa['penghasilan_ayah']],
            ['label' => 'Alamat', 'value' => $data_siswa['alamat']]
        ] as $field)
            <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
                <span>{{ $field['label'] }}</span>
                <span class="font-light text-xs">{{ $field['value'] }}</span>
            </div>
        @endforeach
        </div>
        <div class="space-y-4">
            <div class="font-medium">Berkas</div>
            @foreach($data_berkas as $berkas)
                <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
                    <span>{{ $berkas['nama_file'] }}</span>
                    <span class="font-light text-xs">{{ $berkas['url_file'] }}</span>
                </div>
            @endforeach
        </div>
    <button class="fixed bottom-20 right-4 w-24 flex justify-center text-sm bg-[#51C2FF] text-white p-2 rounded-lg shadow-lg z-50">
        Edit
    </button>
@endsection