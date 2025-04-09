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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->enum('status', ['published', 'draft'])->default('draft');
            $table->timestamp('published_at')->nullable(); // Optional: specific publish date

            // Foreign keys
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Author
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null'); // Category

            // SEO Fields (similar to pages)
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();

            // Optional: Featured image
            $table->string('featured_image_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
