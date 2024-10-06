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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Foreign key to Categories table
            $table->decimal('amount', 15, 2); // Budget amount
            $table->enum('time_range', ['week', 'month', 'quarter', 'year', 'custom']); // Time range (week, month, etc.)
            $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade'); // Foreign key to Wallets table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key to Users table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
