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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // Unique URL slug
            $table->longText('content')->nullable(); // Page content (can be HTML, Markdown, JSON, etc.)
            $table->enum('status', ['published', 'draft'])->default('draft'); // Page status
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Author (optional, set null if user deleted)

            // SEO Fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable(); // Optional keywords
            $table->string('canonical_url')->nullable(); // Optional canonical URL

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
