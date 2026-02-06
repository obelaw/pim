<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Obelaw\Pim\Base\MigrationBase;

return new class extends MigrationBase
{
    public function up(): void
    {
        Schema::create($this->prefix . 'unit_of_measures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('abbreviation')->unique();
            $table->timestamps();
        });

        Schema::create($this->prefix . 'uom_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_uom_id')->constrained($this->prefix . 'unit_of_measures')->cascadeOnDelete();
            $table->foreignId('to_uom_id')->constrained($this->prefix . 'unit_of_measures')->cascadeOnDelete();
            $table->decimal('conversion_factor', 12, 4); // 1 Box = 12 Pieces -> factor 12
            $table->timestamps();

            $table->unique(['from_uom_id', 'to_uom_id']);
        });

        Schema::table($this->prefix . 'products', function (Blueprint $table) {
            $table->foreignId('uom_id')->nullable()->after('product_type')->constrained($this->prefix . 'unit_of_measures')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix . 'products', function (Blueprint $table) {
            $table->dropForeign(['uom_id']);
            $table->dropColumn('uom_id');
        });

        Schema::dropIfExists($this->prefix . 'uom_conversions');
        Schema::dropIfExists($this->prefix . 'unit_of_measures');
    }
};
