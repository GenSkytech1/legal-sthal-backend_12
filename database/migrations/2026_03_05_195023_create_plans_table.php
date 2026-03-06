<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();

            $table->string('plan_name');
            $table->text('description')->nullable();

            // Pricing
            $table->decimal('amount', 20, 2)->default(0);
            $table->decimal('discount_percent', 10, 2)->default(0);
            $table->decimal('amount_after_discount', 20, 2)->default(0);

            // Image
            $table->string('image')->nullable();

            // Audit Fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};