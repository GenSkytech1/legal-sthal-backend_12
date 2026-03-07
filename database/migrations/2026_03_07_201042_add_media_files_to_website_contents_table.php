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
            $table->string('why_choose_image')->nullable()->after('why_choose_us');
        });
    }

    public function down(): void
    {
        Schema::table('website_contents', function (Blueprint $table) {
            $table->dropColumn(['why_choose_image']);
        });
    }
};
