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
        Schema::create('tbl_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_booking')->constrained('tbl_booking')->onDelete('cascade');
            $table->string('kode_transaksi', 50);
            $table->float('total_transaksi');
            $table->enum('status_transaksi', [
                'menunggu_pembayaran',
                'dp',
                'batal',
                'lunas',
            ])->default('menunggu_pembayaran');
            $table->float('total_dp')->nullable();
            $table->json('detail_transaksi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_transaksi');
    }
};
