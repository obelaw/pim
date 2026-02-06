<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Obelaw\Pim\Base\MigrationBase;

return new class extends MigrationBase
{
    public function up(): void
    {
        Schema::create($this->prefix . 'attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('type')->default('text');
            $table->timestamps();
        });

        Schema::create($this->prefix . 'attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained($this->prefix . 'attributes')->cascadeOnDelete();
            $table->string('value');
            $table->timestamps();
        });

        Schema::create($this->prefix . 'products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->enum('product_type', ['simple', 'configurable'])->default('simple');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('special_price', 10, 2)->nullable();
            $table->date('special_price_from')->nullable();
            $table->date('special_price_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create($this->prefix . 'product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained($this->prefix . 'products')->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('special_price', 10, 2)->nullable();
            $table->date('special_price_from')->nullable();
            $table->date('special_price_to')->nullable();
            $table->integer('stock')->default(0);
            $table->timestamps();
        });

        Schema::create($this->prefix . 'product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained($this->prefix . 'products')->cascadeOnDelete();
            $table->foreignId('attribute_value_id')->constrained($this->prefix . 'attribute_values')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create($this->prefix . 'product_variant_attribute_values', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('product_variant_id');
            $table->foreign('product_variant_id', 'opvav_variant_id_foreign')
                  ->references('id')->on($this->prefix . 'product_variants')
                  ->cascadeOnDelete();

            $table->foreignId('attribute_value_id');
            $table->foreign('attribute_value_id', 'opvav_value_id_foreign')
                  ->references('id')->on($this->prefix . 'attribute_values')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix . 'product_variant_attribute_values');
        Schema::dropIfExists($this->prefix . 'product_attribute_values');
        Schema::dropIfExists($this->prefix . 'product_variants');
        Schema::dropIfExists($this->prefix . 'products');
        Schema::dropIfExists($this->prefix . 'attribute_values');
        Schema::dropIfExists($this->prefix . 'attributes');
    }
};
