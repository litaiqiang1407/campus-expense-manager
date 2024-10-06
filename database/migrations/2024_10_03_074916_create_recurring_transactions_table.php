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
        Schema::create('recurring_transactions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Foreign key to Categories table
            $table->decimal('amount', 15, 2); // Transaction amount
            $table->enum('type', ['income', 'expense']); // Type of transaction (income/expense)
            $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade'); // Foreign key to Wallets table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key to Users table
            $table->date('start_date'); // Start date of recurring transaction
            $table->date('end_date')->nullable(); // End date (nullable for indefinite transactions)
            $table->string('frequency'); // Frequency (e.g., daily, weekly)
            $table->text('note')->nullable(); // Optional note
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_transactions');
    }
};
