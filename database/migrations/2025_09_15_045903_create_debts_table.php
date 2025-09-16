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
    Schema::create('debts', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal_utang');
        $table->date('tanggal_jatuh_tempo')->nullable();
        $table->string('pemberi_utang');
        $table->text('catatan')->nullable();
        $table->decimal('jumlah', 15, 2);
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
