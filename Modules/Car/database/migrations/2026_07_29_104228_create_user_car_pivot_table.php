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
        Schema::create('user_car', function (Blueprint $table) {
            $table->id();
            $table->slug();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('car_id')->constrained();
            $table->date('manufactured_at')->nullable();
            $table->foreignId('color_id')->constrained();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_car');
    }
};
