<?php

use Obelaw\Pim\Interfaces\StockManagerInterface;
use Obelaw\Pim\Managers\PimStockManager;
use Obelaw\Pim\Models\Product;
use Obelaw\Pim\Models\ProductStock;
use Obelaw\Pim\Models\ProductVariant;
use Obelaw\Pim\Facades\PIM;

beforeEach(function () {
    // Ensure we are using the PimStockManager via PIM Service config
    PIM::init([
        'default_stock_manager' => PimStockManager::class,
    ]);
});

//
// Product Stock Tests
//
it('can get stock information for a product', function () {
    $product = Product::create([
        'name' => 'Test Product',
        'sku' => 'TEST-SKU-001',
    ]);

    ProductStock::create([
        'stockable_type' => Product::class,
        'stockable_id' => $product->id,
        'physical_stock' => 100,
        'reserved_stock' => 0,
        'available_stock' => 100,
    ]);

    $manager = PIM::stockManager();

    expect($manager->getPhysical($product))->toBe(100.0);
    expect($manager->getAvailable($product))->toBe(100.0);
    expect($manager->getReserved($product))->toBe(0.0);
});

it('can reserve product stock', function () {
    $product = Product::create([
        'name' => 'Reserve Product',
        'sku' => 'TEST-SKU-002',
    ]);

    ProductStock::create([
        'stockable_type' => Product::class,
        'stockable_id' => $product->id,
        'physical_stock' => 50,
        'reserved_stock' => 0,
        'available_stock' => 50,
    ]);

    $manager = PIM::stockManager();
    $manager->reserve($product, 10);

    expect($manager->getReserved($product))->toBe(10.0);
    expect($manager->getAvailable($product))->toBe(40.0);
});

it('throws exception when reserving more than available product stock', function () {
    $product = Product::create([
        'name' => 'Low Stock Product',
        'sku' => 'TEST-SKU-003',
    ]);

    ProductStock::create([
        'stockable_type' => Product::class,
        'stockable_id' => $product->id,
        'physical_stock' => 5,
        'reserved_stock' => 0,
        'available_stock' => 5,
    ]);

    $manager = PIM::stockManager();

    expect(fn() => $manager->reserve($product, 10))
        ->toThrow(Exception::class, 'Local PIM: Insufficient stock');
});

it('can release product reserved stock', function () {
    $product = Product::create([
        'name' => 'Release Product',
        'sku' => 'TEST-SKU-004',
    ]);

    ProductStock::create([
        'stockable_type' => Product::class,
        'stockable_id' => $product->id,
        'physical_stock' => 50,
        'reserved_stock' => 20,
        'available_stock' => 30, 
    ]);

    $manager = PIM::stockManager();
    $manager->release($product, 10);

    expect($manager->getReserved($product))->toBe(10.0);
    expect($manager->getAvailable($product))->toBe(40.0);
});

it('can fulfill product stock', function () {
    $product = Product::create([
        'name' => 'Fulfill Product',
        'sku' => 'TEST-SKU-005',
    ]);

    ProductStock::create([
        'stockable_type' => Product::class,
        'stockable_id' => $product->id,
        'physical_stock' => 50,
        'reserved_stock' => 10,
        'available_stock' => 40,
    ]);

    $manager = PIM::stockManager();
    
    // Fulfill the reserved amount
    $manager->fulfill($product, 10);

    expect($manager->getPhysical($product))->toBe(40.0);
    expect($manager->getReserved($product))->toBe(0.0);
    expect($manager->getAvailable($product))->toBe(40.0);
});

//
// Product Variant Stock Tests
//
it('can get stock information for a variant', function () {
    $product = Product::create(['name' => 'Var Product', 'sku' => 'VAR-P-001']);
    $variant = ProductVariant::create([
        'product_id' => $product->id,
        'sku' => 'VAR-SKU-001',
        'price' => 100,
    ]);

    ProductStock::create([
        'stockable_type' => ProductVariant::class,
        'stockable_id' => $variant->id,
        'physical_stock' => 200,
        'reserved_stock' => 50,
        'available_stock' => 150,
    ]);

    $manager = PIM::stockManager();

    expect($manager->getPhysical($variant))->toBe(200.0);
    expect($manager->getAvailable($variant))->toBe(150.0);
    expect($manager->getReserved($variant))->toBe(50.0);
});

it('can reserve variant stock', function () {
    $product = Product::create(['name' => 'Var Reserve', 'sku' => 'VAR-P-002']);
    $variant = ProductVariant::create([
        'product_id' => $product->id,
        'sku' => 'VAR-SKU-002',
        'price' => 100,
    ]);

    ProductStock::create([
        'stockable_type' => ProductVariant::class,
        'stockable_id' => $variant->id,
        'physical_stock' => 100,
        'reserved_stock' => 0,
        'available_stock' => 100,
    ]);

    $manager = PIM::stockManager();
    $manager->reserve($variant, 25);

    expect($manager->getReserved($variant))->toBe(25.0);
    expect($manager->getAvailable($variant))->toBe(75.0);
});

it('throws exception when reserving more than available variant stock', function () {
    $product = Product::create(['name' => 'Var Low', 'sku' => 'VAR-P-003']);
    $variant = ProductVariant::create([
        'product_id' => $product->id,
        'sku' => 'VAR-SKU-003',
        'price' => 100,
    ]);

    ProductStock::create([
        'stockable_type' => ProductVariant::class,
        'stockable_id' => $variant->id,
        'physical_stock' => 10,
        'reserved_stock' => 0,
        'available_stock' => 10,
    ]);

    $manager = PIM::stockManager();

    expect(fn() => $manager->reserve($variant, 20))
        ->toThrow(Exception::class, 'Local PIM: Insufficient stock');
});

it('can access variant stock via model trait', function () {
    $product = Product::create(['name' => 'Var Trait', 'sku' => 'VAR-P-004']);
    $variant = ProductVariant::create([
        'product_id' => $product->id,
        'sku' => 'VAR-SKU-004',
        'price' => 100,
    ]);

    ProductStock::create([
        'stockable_type' => ProductVariant::class,
        'stockable_id' => $variant->id,
        'physical_stock' => 500,
        'reserved_stock' => 0,
        'available_stock' => 500,
    ]);

    expect($variant->inventory()->getAvailable($variant))->toBe(500.0);
    
    $variant->inventory()->reserve($variant, 100);

    expect($variant->getReservedStock())->toBe(100.0);
});

it('can access stock properties via helper methods', function () {
    $product = Product::create(['name' => 'Helper Product', 'sku' => 'HELP-P-001']);
    
    ProductStock::create([
        'stockable_type' => Product::class,
        'stockable_id' => $product->id,
        'physical_stock' => 1000,
        'reserved_stock' => 200,
        'available_stock' => 800,
    ]);

    expect($product->getPhysicalStock())->toBe(1000.0);
    expect($product->getReservedStock())->toBe(200.0);
    expect($product->getAvailableStock())->toBe(800.0);
});

