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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->slug();
            $table->foreignId('user_id')->constrained();
            $table->string('phone')->nullable();
            $table->foreignId('city_id')->nullable()->constrained();
            $table->text('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('website')->nullable();
            $table->string('tax_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
