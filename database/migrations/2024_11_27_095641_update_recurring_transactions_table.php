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
        Schema::table('recurring_transactions', function (Blueprint $table) {
            // Sửa lại cột frequency thành enum với các giá trị cụ thể
            $table->enum('type', ['Repeat Daily', 'Repeat Weekly', 'Repeat Monthly', 'Repeat Yearly'])->default('Repeat Daily')->change();

            // Thêm các cột mới
            $table->integer('interval')->default(1)->after('frequency');

            $table->dropColumn('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recurring_transactions', function (Blueprint $table) {
            // Thêm lại cột end_date
            $table->date('end_date')->nullable()->after('start_date');

            // Xóa các cột mới
            $table->dropColumn('interval');

            // Khôi phục lại cột frequency (nếu cần)
            $table->string('frequency')->default('Repeat Daily')->change();
        });
    }
};
