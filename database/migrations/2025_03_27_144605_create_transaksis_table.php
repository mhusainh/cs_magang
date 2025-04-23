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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('tagihan_id')->constrained('tagihans')->onDelete('cascade');
            $table->string('status')->nullable();
            $table->integer('total')->nullable();
            $table->string('created_time', 8)->nullable();
            $table->string('va_number', 20)->nullable();
            $table->string('transaction_qr_id', 8)->nullable();
            $table->string('method', 10)->nullable();
            $table->string('ref_no', 8)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
