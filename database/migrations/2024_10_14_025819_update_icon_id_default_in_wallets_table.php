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
        $table->foreignId('icon_id')->default(1)->constrained('icons')->onDelete('cascade')->change(); // Thay đổi icon_id thành mặc định 1
    });
}

public function down(): void
{
    Schema::table('wallets', function (Blueprint $table) {
        $table->dropForeign(['icon_id']); // Xóa ràng buộc nếu cần
        $table->foreignId('icon_id')->nullable()->constrained('icons')->onDelete('cascade')->change(); // Khôi phục về giá trị mặc định ban đầu nếu cần
    });
}
};
