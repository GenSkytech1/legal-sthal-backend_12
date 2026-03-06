<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('hero_title')->nullable();
            $table->string('hero_price')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->json('also_get')->nullable();
            $table->string('form_title')->nullable();
            $table->json('how_it_works')->nullable();
            $table->json('process_list')->nullable();
            $table->json('benefits')->nullable();
            $table->json('requirements')->nullable();
            $table->json('documents')->nullable();
            $table->text('fees_cost')->nullable();
            $table->json('what_you_get')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_pages');
    }
};
