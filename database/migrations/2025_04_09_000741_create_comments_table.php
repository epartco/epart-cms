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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade'); // Foreign key to articles table
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table (author)
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade'); // Self-referencing key for replies
            $table->text('content');
            $table->string('status')->default('approved'); // e.g., approved, pending, spam
            $table->timestamps();
            $table->softDeletes(); // Optional: Add soft delete functionality
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
