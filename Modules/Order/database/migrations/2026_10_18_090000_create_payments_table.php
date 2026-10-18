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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->slug();
            $table->foreignId('order_id')->constrained()->restrictOnDelete();
            $table->decimal('amount', 18, 4);
            $table->string('method')->index();
            $table->date('paid_at');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['order_id', 'paid_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
