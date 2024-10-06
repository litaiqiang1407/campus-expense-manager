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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Wallet name
            $table->foreignId('wallet_type_id')->constrained('wallet_types')->onDelete('cascade'); // Foreign key to Wallet_Types table
            $table->decimal('balance', 15, 2); // Wallet balance
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key to Users table
            $table->foreignId('icon_id')->constrained('icons')->onDelete('cascade'); // Foreign key to Icons table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
