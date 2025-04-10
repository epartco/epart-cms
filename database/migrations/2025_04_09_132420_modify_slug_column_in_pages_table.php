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
        Schema::table('pages', function (Blueprint $table) {
            // Modify the slug column to be nullable. The unique constraint already exists.
            $table->string('slug')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // Revert the change: make slug non-nullable again. The unique constraint remains.
            // This might fail if there are existing NULL slugs.
            $table->string('slug')->nullable(false)->change();
        });
    }
};
