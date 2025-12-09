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
        Schema::create('tbl_portofolio', function (Blueprint $table) {
            $table->id();
            $table->string('judul_portofolio', 128);
            $table->text('deskripsi_portofolio');
            $table->string('foto_portofolio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_portofolio');
    }
};
