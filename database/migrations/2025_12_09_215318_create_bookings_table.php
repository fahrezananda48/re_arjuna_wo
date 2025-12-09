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
        Schema::create('tbl_booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('tbl_customer')->onDelete('cascade');
            $table->string('nama_cpp', 128);
            $table->string('nama_cpw', 128);
            $table->string('nama_ayah', 128);
            $table->string('nama_ibu', 128);
            $table->text('alamat');
            $table->string('nomor_telp', 20);
            $table->date('tanggal_acara');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_booking');
    }
};
