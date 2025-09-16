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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal');
        $table->enum('tipe', ['pemasukan', 'pengeluaran']);
        $table->text('catatan')->nullable();
        $table->decimal('jumlah', 15, 2);
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->enum('status', ['pending', 'selesai'])->default('selesai');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
