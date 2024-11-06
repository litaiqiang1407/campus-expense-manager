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
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropForeign(['wallet_type_id']);
            $table->dropColumn('wallet_type_id'); // Xóa cột wallet_type_id
            $table->string('wallet_type_name'); // Thêm cột wallet_type_name
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->foreignId('wallet_type_id')->constrained('wallet_types')->onDelete('cascade');
            $table->dropColumn('wallet_type_name');
        });
    }
};
