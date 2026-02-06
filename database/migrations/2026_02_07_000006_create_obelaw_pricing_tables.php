<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Obelaw\Pim\Base\MigrationBase;

return new class extends MigrationBase
{
    public function up(): void
    {
        // Price Lists Table
        Schema::create($this->prefix . 'price_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('currency_code', 3); // ISO 4217 Currency Code
            
            // Validity Period for the entire list
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index for performance on date range lookups
            $table->index(['start_date', 'end_date', 'is_active']);
        });

        // Prices Table (Polymorphic)
        Schema::create($this->prefix . 'prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_list_id')->constrained($this->prefix . 'price_lists')->cascadeOnDelete();
            
            // Polymorphic: Product or ProductVariant
            $table->morphs('priceable');

            $table->decimal('price', 19, 4);
            $table->decimal('special_price', 19, 4)->nullable();

            $table->timestamps();

            // Compound index for fast lookup by entity and list
            $table->index(['price_list_id', 'priceable_type', 'priceable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix . 'prices');
        Schema::dropIfExists($this->prefix . 'price_lists');
    }
};
