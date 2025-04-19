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
        Schema::table('menus', function (Blueprint $table) {
            // Check if the column exists before dropping to avoid errors on fresh installs
            if (Schema::hasColumn('menus', 'url')) {
                $table->dropColumn('url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // Add the column back. Adjust the definition based on the original if needed.
            // Assuming it was a string and nullable based on previous context.
            // If it had specific constraints (length, default), add them here.
            $table->string('url')->nullable()->after('name'); // Place it back where it roughly was
        });
    }
};
