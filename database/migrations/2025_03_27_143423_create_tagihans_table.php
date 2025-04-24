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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('nama_tagihan')->nullable();
            $table->integer('total')->nullable();
            $table->boolean('status')->default(false);
            $table->string('va_number', 20)->nullable();
            $table->string('transaction_qr_id', 8)->nullable();
            $table->string('raw_qr_data', 1000)->nullable();
            $table->string('created_time', 8)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
