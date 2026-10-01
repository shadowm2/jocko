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
        Schema::create('item_brands', function (Blueprint $table) {
            $table->id();
            $table->slug();
            $table->foreignId('item_id')
                ->constrained('items')
                ->cascadeOnDelete();

            $table->foreignId('brand_id')
                ->constrained('brands')
                ->restrictOnDelete();

            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->string('part_number')->nullable();

            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->decimal('sale_price', 15, 2)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index('sku');
            $table->index('barcode');
            $table->index('part_number');
            $table->index('is_active');

            $table->unique(
                ['item_id', 'brand_id'],
                'item_brands_item_id_brand_id_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_brands');
    }
};
