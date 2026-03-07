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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('source')->comment('Replacing enum with string')->change();
            $table->string('city')->nullable()->after('phone');
            $table->string('service_type')->nullable()->after('city');
            $table->text('message')->nullable()->after('service_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Revert changes (approximate, since enum values were specific)
            // $table->enum('source', ['google_ads', 'meta_ads', 'google_form'])->change(); 
            $table->dropColumn(['city', 'service_type', 'message']);
        });
    }
};
