<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat data dummy untuk users
        $users = [
            [
                'id' => 1,
                'no_telp' => '081234567890',
                'role' => 'user',
                'status' => 1, // Sudah membayar
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'no_telp' => '081234567891',
                'role' => 'user',
                'status' => 0, // Belum membayar
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'no_telp' => '081234567892',
                'role' => 'admin',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('users')->insert($users);

        // Membuat data dummy untuk jurusan
        $jurusan = [
            [
                'id' => 1,
                'jurusan' => 'IPA',
                'jenjang_sekolah' => 'SMA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'jurusan' => 'IPS',
                'jenjang_sekolah' => 'SMA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'jurusan' => 'Teknik Komputer dan Jaringan',
                'jenjang_sekolah' => 'SMK',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'jurusan' => 'Rekayasa Perangkat Lunak',
                'jenjang_sekolah' => 'SMK',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('jurusan')->insert($jurusan);

        // Membuat data dummy untuk peserta_ppdbs
        $peserta = [
            [
                'id' => 1,
                'user_id' => 1,
                'nisn' => '1234567890',
                'nis' => '987654321',
                'nama' => 'Budi Santoso',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2005-05-15',
                'jenis_kelamin' => 'L',
                'agama' => 'Islam',
                'email' => 'budi@example.com',
                'no_telp' => '081234567890',
                'jenjang_sekolah' => 'SMA',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'jurusan1_id' => 1,
                'jurusan2_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'nisn' => '0987654321',
                'nis' => '1234567890',
                'nama' => 'Siti Rahayu',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2006-08-20',
                'jenis_kelamin' => 'P',
                'agama' => 'Islam',
                'email' => 'siti@example.com',
                'no_telp' => '081234567891',
                'jenjang_sekolah' => 'SMK',
                'alamat' => 'Jl. Pahlawan No. 45, Bandung',
                'jurusan1_id' => 3,
                'jurusan2_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('peserta_ppdbs')->insert($peserta);

        // Membuat data dummy untuk ketentuan_berkas
        $ketentuanBerkas = [
            [
                'id' => 1,
                'nama' => 'Kartu Keluarga',
                'jenjang_sekolah' => 'SMA',
                'is_required' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nama' => 'Ijazah',
                'jenjang_sekolah' => 'SMA',
                'is_required' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nama' => 'Kartu Keluarga',
                'jenjang_sekolah' => 'SMK',
                'is_required' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nama' => 'Ijazah',
                'jenjang_sekolah' => 'SMK',
                'is_required' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('ketentuan_berkas')->insert($ketentuanBerkas);

        // Membuat data dummy untuk berkas
        $berkas = [
            [
                'id' => 1,
                'peserta_id' => 1,
                'nama_file' => 'KK_Budi.pdf',
                'ketentuan_berkas_id' => 1,
                'url_file' => 'https://example.com/files/kk_budi.pdf',
                'public_id' => 'files/kk_budi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'peserta_id' => 1,
                'nama_file' => 'Ijazah_Budi.pdf',
                'ketentuan_berkas_id' => 2,
                'url_file' => 'https://example.com/files/ijazah_budi.pdf',
                'public_id' => 'files/ijazah_budi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('berkas')->insert($berkas);

        // Membuat data dummy untuk pekerjaan_ortus
        $pekerjaanOrtu = [
            [
                'id' => 1,
                'pekerjaan' => 'PNS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'pekerjaan' => 'Wiraswasta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'pekerjaan' => 'Karyawan Swasta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('pekerjaan_ortus')->insert($pekerjaanOrtu);

        // Membuat data dummy untuk biodata_ortus
        $biodataOrtu = [
            [
                'id' => 1,
                'peserta_id' => 1,
                'nama_ayah' => 'Ahmad Santoso',
                'nama_ibu' => 'Siti Aminah',
                'pekerjaan_ayah_id' => 1,
                'pekerjaan_ibu_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'peserta_id' => 2,
                'nama_ayah' => 'Budi Raharjo',
                'nama_ibu' => 'Dewi Susanti',
                'pekerjaan_ayah_id' => 3,
                'pekerjaan_ibu_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('biodata_ortus')->insert($biodataOrtu);

        // Membuat data dummy untuk tagihans
        $tagihan = [
            [
                'id' => 1,
                'peserta_id' => 1,
                'nama' => 'Biaya Pendaftaran',
                'nominal' => 500000,
                'status' => 1, // Sudah dibayar
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'peserta_id' => 2,
                'nama' => 'Biaya Pendaftaran',
                'nominal' => 500000,
                'status' => 0, // Belum dibayar
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('tagihans')->insert($tagihan);

        // Membuat data dummy untuk transaksis
        $transaksi = [
            [
                'id' => 1,
                'tagihan_id' => 1,
                'nominal' => 500000,
                'metode_pembayaran' => 'Transfer Bank',
                'bukti_pembayaran' => 'https://example.com/bukti/transfer1.jpg',
                'status' => 1, // Sudah diverifikasi
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('transaksis')->insert($transaksi);

        // Membuat data dummy untuk progress_users
        $progressUser = [
            [
                'id' => 1,
                'user_id' => 1,
                'biodata' => 1,
                'berkas' => 1,
                'pembayaran' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'biodata' => 1,
                'berkas' => 0,
                'pembayaran' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('progress_users')->insert($progressUser);

        // Membuat data dummy untuk pesan
        $pesan = [
            [
                'id' => 1,
                'user_id' => 1,
                'judul' => 'Informasi Pendaftaran',
                'isi' => 'Selamat, pendaftaran Anda telah berhasil. Silakan lengkapi berkas-berkas yang diperlukan.',
                'is_read' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'judul' => 'Pengingat Pembayaran',
                'isi' => 'Mohon segera melakukan pembayaran biaya pendaftaran untuk melanjutkan proses pendaftaran.',
                'is_read' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('pesan')->insert($pesan);

        // Membuat data dummy untuk media
        $media = [
            [
                'id' => 1,
                'nama' => 'Jadwal Pendaftaran',
                'jenis' => 'jadwal',
                'url' => 'https://example.com/media/jadwal.pdf',
                'public_id' => 'media/jadwal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nama' => 'Pengajuan Biaya 2023',
                'jenis' => 'pengajuan_biaya',
                'url' => 'https://example.com/media/pengajuan_biaya.pdf',
                'public_id' => 'media/pengajuan_biaya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('media')->insert($media);

        // Membuat data dummy untuk pengajuan_biaya
        $pengajuanBiaya = [
            [
                'id' => 1,
                'nama' => 'Pengajuan Biaya Pendaftaran',
                'nominal' => 500000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nama' => 'Pengajuan Biaya Seragam',
                'nominal' => 750000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('pengajuan_biaya')->insert($pengajuanBiaya);

        // Membuat data dummy untuk biaya_pendaftaran
        $biayaPendaftaran = [
            [
                'id' => 1,
                'jenjang_sekolah' => 'SMA',
                'nominal' => 500000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'jenjang_sekolah' => 'SMK',
                'nominal' => 450000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('biaya_pendaftaran')->insert($biayaPendaftaran);
    }
}