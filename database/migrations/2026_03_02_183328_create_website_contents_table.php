<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('website_contents', function (Blueprint $table) {
            $table->id();
            $table->string('header_logo')->nullable();
            $table->json('header_nav')->nullable();
            $table->json('sub_nav')->nullable();
            
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_image')->nullable();
            
            $table->json('our_services')->nullable();
            
            $table->text('footer_about')->nullable();
            $table->text('footer_address')->nullable();
            $table->string('footer_phone')->nullable();
            $table->string('footer_email')->nullable();
            $table->json('footer_links')->nullable();
            $table->json('footer_social_links')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('website_contents');
    }
};
