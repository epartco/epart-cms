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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->onDelete('cascade'); // Foreign key to menus table
            $table->string('title');
            $table->string('url');
            $table->string('target')->default('_self'); // Default target is _self
            $table->unsignedBigInteger('parent_id')->nullable(); // Nullable for top-level items
            $table->integer('order')->default(0); // Order within the same level
            $table->timestamps();

            // Optional: Add foreign key constraint for parent_id referencing the same table
            $table->foreign('parent_id')->references('id')->on('menu_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
