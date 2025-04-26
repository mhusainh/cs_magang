<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peserta_ppdbs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nisn')->nullable();
            $table->string('nis')->nullable();
            $table->string('nama')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('agama', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('no_telp', 15)->nullable();
            $table->string('jenjang_sekolah')->nullable();
            $table->text('alamat')->nullable();
            $table->foreignId('jurusan1_id')->nullable()->constrained('jurusan');
            $table->integer('pengajuan_biaya')->nullable();
            $table->integer('wakaf')->nullable();
            $table->integer('spp')->nullable();
            $table->integer('book_vee')->nullable();
            $table->string('status', 20)->nullable(); // Menambahkan kolom status dengan tipe data string
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_ppdbs');
    }
};
