@extends('layouts.app')

@section('content')
    <div class="text-2xl flex w-full justify-center font-bold mb-4">Unggah Berkas</div>
    <form action="" class="space-y-6">
        <!-- Pas Foto -->
        <div class="text-xs space-y-2">
            <label class="block">Pas foto</label>
            <div class="flex">
                <input type="file" id="pas_foto" class="hidden" accept="image/*">
                <label for="pas_foto" class="w-full h-8 px-3 border rounded-lg flex items-center cursor-pointer ">
                    <span class="text-white font-medium bg-[#51C2FF] p-1 rounded-sm shadow-xs">Pilih file</span>
                    <span class="text-xs text-gray-400 block pl-2">Tidak ada file yang dipilih</span>
                </label>
                <span class="text-xs text-gray-400 block pl-2"><img src="{{ asset('assets/svg/Trash.svg') }}"
                        alt=""></span>
            </div>
        </div>

        <!-- Akta -->
        <div class="text-xs space-y-2">
            <label class="block">Akta</label>
            <div class="flex">
                <input type="file" id="akta" class="hidden">
                <label for="akta" class="w-full h-8 px-3 border rounded-lg flex items-center cursor-pointer bg-white">
                    <span class="text-white font-medium bg-[#51C2FF] p-1 rounded-sm shadow-xs">Pilih file</span>
                    <span class="text-xs text-gray-400 block pl-2">Tidak ada file yang dipilih</span>
                </label>
                <span class="text-xs text-gray-400 block pl-2"><img src="{{ asset('assets/svg/Trash.svg') }}"
                        alt=""></span>
            </div>
        </div>

        <!-- Kartu Keluarga -->
        <div class="text-xs space-y-2">
            <label class="block">Kartu Keluarga</label>
            <div class="flex">
                <input type="file" id="kartu_keluarga" class="hidden">
                <label for="kartu_keluarga"
                    class="w-full h-8 px-3 border rounded-lg flex items-center cursor-pointer bg-white">
                    <span class="text-white font-medium bg-[#51C2FF] p-1 rounded-sm shadow-xs">Pilih file</span>
                    <span class="text-xs text-gray-400 block pl-2">Tidak ada file yang dipilih</span>
                </label>
                <span class="text-xs text-gray-400 block pl-2"><img src="{{ asset('assets/svg/Trash.svg') }}"
                        alt=""></span>
            </div>
        </div>

        <!-- Ijazah -->
        <div class="text-xs space-y-2">
            <label class="block">Ijazah</label>
            <div class="flex">
                <input type="file" id="ijazah" class="hidden">
                <label for="ijazah" class="w-full h-8 px-3 border rounded-lg flex items-center cursor-pointer bg-white">
                    <span class="text-white font-medium bg-[#51C2FF] p-1 rounded-sm shadow-xs">Pilih file</span>
                    <span class="text-xs text-gray-400 block pl-2">Tidak ada file yang dipilih</span>
                </label>
                <span class="text-xs text-gray-400 block pl-2"><img src="{{ asset('assets/svg/Trash.svg') }}"
                        alt=""></span>
            </div>
        </div>

        <!-- Tombol Kirim -->
        <button type="submit"
            class="w-full bg-ppdb-green text-white py-2 rounded-lg font-semibold bg-[#51C2FF] cursor-pointer transition-colors">
            Kirim
        </button>
    </form>

    <script>
        // Script untuk update nama file
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name || 'Tidak ada file yang dipilih';
                this.nextElementSibling.querySelector('span').textContent = fileName;
                this.parentElement.nextElementSibling.textContent = fileName;
            });
        });
    </script>
@endsection
