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
        Schema::create('warehouse_items', function (Blueprint $table) {
            $table->id();
            $table->slug();
            $table->foreignId('warehouse_id')
                ->constrained('warehouses')
                ->cascadeOnDelete();

            $table->foreignId('item_id')
                ->constrained('items')
                ->cascadeOnDelete();

            $table->decimal('quantity', 15, 3)
                ->default(0);

            $table->decimal('min_quantity', 15, 3)
                ->default(null)
                ->nullable();

            $table->decimal('max_quantity', 15, 3)
                ->default(null)
                ->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique([
                'warehouse_id',
                'item_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_items');
    }
};
