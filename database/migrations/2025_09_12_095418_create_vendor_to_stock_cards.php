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
        Schema::table('stock_cards', function (Blueprint $table) {
            $table->string('vendor', 50)->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            Schema::table('stock_cards', function (Blueprint $table){
            if(Schema::hasColumn('stock_cards', 'vendor')){
                $table->dropColumn(('vendor'));
            }
        });
    }
};
