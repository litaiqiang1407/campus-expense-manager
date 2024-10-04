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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Name of the category (e.g., Food, Entertainment)
            $table->foreignId('parent_id')->nullable()->constrained('categories'); // ID of the parent category for hierarchical relationships
            $table->enum('type', ['expense', 'income']); // Category type (either expense or income)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key to the Users table
            $table->foreignId('icon_id')->constrained('icons')->onDelete('cascade'); // Foreign key to the Icons table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};