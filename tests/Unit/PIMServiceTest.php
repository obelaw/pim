<?php

use Obelaw\Pim\Services\PIMService;
use Obelaw\Pim\Repositories\ProductRepository;
use Obelaw\Pim\Repositories\CategoryRepository;
use Obelaw\Pim\Interfaces\StockManagerInterface;
use Obelaw\Pim\Services\UomConverter;

it('proxies products to ProductRepository', function () {
    $pim = new PIMService();
    expect($pim->products())->toBeInstanceOf(ProductRepository::class);
});

it('proxies categories to CategoryRepository', function () {
    $pim = new PIMService();
    expect($pim->categories())->toBeInstanceOf(CategoryRepository::class);
});

it('provides access to the stock manager', function () {
    $pim = new PIMService();
    // Ensure the interface is bound (usually handled by ServiceProvider, but for unit tests relying on app() we might need to check if binding exists or just check return type if SP is loaded)
    // TestCase loads ObelawPIMServiceProvider so it should be fine.
    
    expect($pim->stockManager())->toBeInstanceOf(StockManagerInterface::class);
});

it('provides access to the uom converter', function () {
    $pim = new PIMService();
    expect($pim->uomConverter())->toBeInstanceOf(UomConverter::class);
});

