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
        Schema::table('budgets', function (Blueprint $table) {
            // Xóa cột không cần thiết
            $table->dropColumn('time_range');

            // Thêm các cột mới
            $table->date('start_date')->nullable()->after('amount'); // Ngày bắt đầu
            $table->date('end_date')->nullable()->after('start_date'); // Ngày kết thúc
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            // Thêm lại cột "time_range" đã xóa
            $table->enum('time_range', ['week', 'month', 'quarter', 'year', 'custom'])->after('amount');

            // Xóa các cột mới
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};
