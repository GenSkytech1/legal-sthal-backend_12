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
        Schema::table('website_contents', function (Blueprint $table) {
            $table->json('why_choose_us')->nullable();
            $table->json('trusted_partners')->nullable();
            $table->json('testimonials')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_contents', function (Blueprint $table) {
            $table->dropColumn(['why_choose_us', 'trusted_partners', 'testimonials']);
        });
    }
};
