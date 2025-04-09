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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained()->onDelete('cascade'); // Foreign key to boards table
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table (author)
            $table->string('title');
            $table->longText('content');
            $table->unsignedInteger('view_count')->default(0);
            $table->string('status')->default('published'); // e.g., published, draft, pending
            $table->boolean('is_notice')->default(false); // For sticky/notice posts
            $table->timestamps();
            $table->softDeletes(); // Optional: Add soft delete functionality
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
