<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Obelaw\Pim\Base\MigrationBase;

return new class extends MigrationBase
{
    public function up(): void
    {
        Schema::table($this->prefix . 'product_variants', function (Blueprint $table) {
            $table->foreignId('uom_id')->nullable()->after('stock')->constrained($this->prefix . 'unit_of_measures')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix . 'product_variants', function (Blueprint $table) {
            $table->dropForeign(['uom_id']);
            $table->dropColumn('uom_id');
        });
    }
};
