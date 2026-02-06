<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Obelaw\Pim\Base\MigrationBase;

return new class extends MigrationBase
{
    public function up(): void
    {
        Schema::create($this->prefix . 'categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->foreignId('parent_id')->nullable()->constrained($this->prefix . 'categories')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create($this->prefix . 'category_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained($this->prefix . 'categories')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained($this->prefix . 'products')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix . 'category_product');
        Schema::dropIfExists($this->prefix . 'categories');
    }
};
