<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Obelaw\Pim\Base\MigrationBase;

return new class extends MigrationBase
{
    public function up(): void
    {
        Schema::create($this->prefix . 'product_stocks', function (Blueprint $table) {
            $table->id();
            $table->morphs('stockable');
            
            $table->decimal('physical_stock', 19, 4)->default(0);
            $table->decimal('reserved_stock', 19, 4)->default(0);
            $table->decimal('available_stock', 19, 4)->default(0);
            
            $table->timestamps();

            $table->unique(['stockable_id', 'stockable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix . 'product_stocks');
    }
};
