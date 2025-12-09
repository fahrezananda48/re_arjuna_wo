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
        Schema::create('tbl_katalog', function (Blueprint $table) {
            $table->id();
            $table->string('nama_katalog', 128);
            $table->float('harga_katalog');
            $table->string('thumbnail_katalog')->nullable();
            $table->text('deskripsi_katalog')->nullable();
            $table->enum('status_katalog', [
                'belum_aktif',
                'aktif',
                'diskon',
            ])->default('belum_aktif');
            $table->bigInteger('diskon_katalog')->nullable();
            $table->json('item_katalog')->nullable();
            $table->json('data_vendor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_katalog');
    }
};
