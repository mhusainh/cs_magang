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
        Schema::create('biodata_ortus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('peserta_ppdbs')->onDelete('cascade');
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('no_telp', 15)->nullable();
            $table->foreignId('pekerjaan_ayah_id')->constrained('pekerjaans')->nullable();
            $table->foreignId('pekerjaan_ibu_id')->constrained('pekerjaans')->nullable();
            $table->foreignId('penghasilan_ortu_id')->constrained('penghasilans')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biodata_ortus');
    }
};
