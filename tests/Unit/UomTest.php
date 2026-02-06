<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Obelaw\Pim\Data\ProductDTO;
use Obelaw\Pim\Data\VariantDTO;
use Obelaw\Pim\Facades\PIM;
use Obelaw\Pim\Models\UnitOfMeasure;
use Obelaw\Pim\Models\UomConversion;
use Obelaw\Pim\Repositories\ProductRepository;

uses(RefreshDatabase::class);

it('can create units of measure', function () {
    $piece = UnitOfMeasure::create(['name' => 'Piece', 'abbreviation' => 'pc']);
    $box = UnitOfMeasure::create(['name' => 'Box', 'abbreviation' => 'bx']);

    expect($piece)->toBeInstanceOf(UnitOfMeasure::class);
    expect($box)->toBeInstanceOf(UnitOfMeasure::class);
});

it('can convert between units', function () {
    $piece = UnitOfMeasure::create(['name' => 'Piece', 'abbreviation' => 'pc']);
    $box = UnitOfMeasure::create(['name' => 'Box', 'abbreviation' => 'bx']);

    // 1 Box = 12 Pieces
    UomConversion::create([
        'from_uom_id' => $box->id,
        'to_uom_id' => $piece->id,
        'conversion_factor' => 12,
    ]);

    $converter = PIM::uomConverter();

    // Convert Box to Piece (12 * 5 = 60)
    $pieces = $converter->convert(5, $box, $piece);
    expect($pieces)->toBe(60.0);

    // Convert Piece to Box (24 / 12 = 2)
    $boxes = $converter->convert(24, $piece, $box);
    expect($boxes)->toBe(2.0);
});

it('can assign uom to product', function () {
    $piece = UnitOfMeasure::create(['name' => 'Piece', 'abbreviation' => 'pc']);

    $dto = new ProductDTO(
        sku: 'TEST-UOM',
        name: 'Product with UOM',
        productType: 'simple',
        price: 10,
        uomId: $piece->id
    );

    $repository = new ProductRepository();
    $product = $repository->create($dto);

    expect($product->uom_id)->toBe($piece->id);
    expect($product->uom->abbreviation)->toBe('pc');
});

it('can assign uom to product variants', function () {
    $piece = UnitOfMeasure::create(['name' => 'Piece', 'abbreviation' => 'pc']);
    $box = UnitOfMeasure::create(['name' => 'Box', 'abbreviation' => 'bx']);

    $variantDto = new VariantDTO(
        sku: 'TEST-VAR-1',
        price: 10,
        stock: 100,
        uomId: $box->id
    );

    $dto = new ProductDTO(
        sku: 'TEST-CONFIG-UOM',
        name: 'Configurable Product with UOM',
        productType: 'configurable',
        price: 10,
        uomId: $piece->id,
        variants: [$variantDto]
    );

    $repository = new ProductRepository();
    $product = $repository->create($dto);

    expect($product->variants)->toHaveCount(1);
    expect($product->variants->first()->uom_id)->toBe($box->id);
    expect($product->uom_id)->toBe($piece->id);
});
