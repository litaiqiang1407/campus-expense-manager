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
        Schema::table('categories', function (Blueprint $table) {
            Schema::table('categories', function (Blueprint $table) {
                // Make 'user_id' nullable
                $table->unsignedBigInteger('user_id')->nullable()->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            Schema::table('categories', function (Blueprint $table) {
                // Revert 'user_id' to be non-nullable if you want to roll back
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
            });
        });
    }
};
