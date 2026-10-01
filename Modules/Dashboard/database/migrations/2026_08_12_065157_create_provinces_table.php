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
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();

            $table->foreignId('country_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->slug();

            $table->boolean('is_active')->default(true);

            $table->unique([
                'country_id',
                'name',
            ]);

            $table->index([
                'country_id',
                'is_active',
            ]);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};
