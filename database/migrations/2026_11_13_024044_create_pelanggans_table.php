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
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pelanggan')->unique(); // Contoh: CUS-00001
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Relasi ke akun login web nantinya
            $table->enum('tipe', ['WORKSHOP', 'RETAIL'])->default('RETAIL');
            $table->string('nama');
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('npwp')->nullable();
            $table->enum('status', ['PENDING', 'ACTIVE', 'SUSPENDED'])->default('ACTIVE');
            $table->enum('tingkat_harga', ['RETAIL', 'WHOLESALE', 'CUSTOM'])->default('RETAIL');
            $table->integer('lama_tempo')->default(0); // Batas hari tempo pembayaran
            $table->bigInteger('batas_kredit')->default(0); // Maksimal nominal kasbon/tempo
            $table->boolean('is_default')->default(false); // Penanda untuk "Pelanggan Umum" di kasir
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
