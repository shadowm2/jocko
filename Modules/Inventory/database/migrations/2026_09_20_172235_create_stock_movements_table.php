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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            $table->slug();

            $table->foreignId('item_id')
                ->constrained('items')
                ->cascadeOnDelete();

            $table->foreignId('warehouse_item_id')
                ->nullable()
                ->constrained('warehouse_items')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('from_warehouse_id')
                ->nullable()
                ->constrained('warehouses')
                ->nullOnDelete();

            $table->foreignId('to_warehouse_id')
                ->nullable()
                ->constrained('warehouses')
                ->nullOnDelete();

            $table->decimal('quantity', 18, 4);

            $table->decimal('unit_cost', 18, 4)->default(0);
            $table->decimal('total_cost', 18, 4)->default(0);

            // Warehouse item quantity right after this movement.
            $table->decimal('balance_after', 18, 4)->nullable();

            $table->string('type')->index();

            $table->nullableMorphs('reference');

            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'item_id',
                'from_warehouse_id',
            ]);

            $table->index([
                'item_id',
                'to_warehouse_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
