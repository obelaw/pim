<?php

use Obelaw\Pim\Managers\WmsStockManager;
use Obelaw\Pim\Models\Product;
use Obelaw\Pim\Facades\PIM;

beforeEach(function () {
    // Configure PIMService to use WmsStockManager
    // This updates the internal config AND binds the interface in the container
    PIM::init([
        'default_stock_manager' => WmsStockManager::class,
    ]);
});

it('returns stubbed stock values from WMS manager', function () {
    $product = Product::create([
        'name' => 'WMS Product',
        'sku' => 'WMS-SKU-001',
    ]);

    $manager = PIM::stockManager();

    // Asserting the stubbed values defined in WmsStockManager
    expect($manager->getPhysical($product))->toBe(9999.0);
    expect($manager->getAvailable($product))->toBe(9999.0);
    expect($manager->getReserved($product))->toBe(0.0);
});

it('can call operational methods without error (smoke test)', function () {
    $product = Product::create([
        'name' => 'WMS Operation Product',
        'sku' => 'WMS-SKU-002',
    ]);

    $manager = PIM::stockManager();

    // These usages should not throw exceptions even if they do nothing internally yet
    $manager->reserve($product, 10);
    $manager->release($product, 5);
    $manager->fulfill($product, 5);

    // Verify state hasn't changed (since stubs are static)
    expect($manager->getPhysical($product))->toBe(9999.0);
    expect($manager->getAvailable($product))->toBe(9999.0);
});

it('delegates to WMS manager via model trait when bound', function () {
    $product = Product::create([
        'name' => 'WMS Trait Product',
        'sku' => 'WMS-SKU-003',
    ]);

    // The interface should have been bound by PIM::init()
    expect($product->inventory())->toBeInstanceOf(WmsStockManager::class);
    
    expect($product->getPhysicalStock())->toBe(9999.0);
    expect($product->getAvailableStock())->toBe(9999.0);
});

it('can access stock properties via helper methods', function () {
    $product = Product::create([
        'name' => 'WMS Helper Product',
        'sku' => 'WMS-SKU-004',
    ]);

    // These should return the WMS stub values via the HasStock trait
    expect($product->getPhysicalStock())->toBe(9999.0);
    expect($product->getReservedStock())->toBe(0.0);
    expect($product->getAvailableStock())->toBe(9999.0);
});

