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
        Schema::create('leads', function (Blueprint $table) {
        $table->id();

        $table->enum('source', ['google_ads', 'meta_ads', 'google_form']);

        // Platform identifiers
        $table->string('platform_lead_id')->nullable()->index();
        $table->string('campaign_id')->nullable();
        $table->string('campaign_name')->nullable();
        $table->string('ad_id')->nullable();
        $table->string('ad_name')->nullable();
        $table->string('form_id')->nullable();

        // User info
        $table->string('name')->nullable();
        $table->string('email')->nullable();
        $table->string('phone')->nullable();

        // CRM info
        $table->string('status')->default('new'); // new, contacted, converted
        $table->string('assigned_to')->nullable();

        // Raw data
        $table->json('custom_fields')->nullable();
        $table->json('raw_payload');

        $table->timestamps();

        $table->unique(['source', 'platform_lead_id']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // drop the table when rolling back
        Schema::dropIfExists('leads');
    }
};
