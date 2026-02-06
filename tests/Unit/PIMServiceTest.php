<?php

use Obelaw\Pim\Services\PIMService;
use Obelaw\Pim\Repositories\ProductRepository;
use Obelaw\Pim\Repositories\CategoryRepository;

it('proxies products to ProductRepository', function () {
    $pim = new PIMService();
    expect($pim->products())->toBeInstanceOf(ProductRepository::class);
});

it('proxies categories to CategoryRepository', function () {
    $pim = new PIMService();
    expect($pim->categories())->toBeInstanceOf(CategoryRepository::class);
});
