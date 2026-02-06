<?php

use Obelaw\Pim\Models\PriceList;
use Obelaw\Pim\Models\Product;
use Obelaw\Pim\Models\ProductVariant;
use Obelaw\Pim\Services\PriceResolverService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('resolves price only if price list is active today', function () {
    // 1. Active List
    $activeList = PriceList::create([
        'name' => 'Active List',
        'currency_code' => 'USD', 
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
        'is_active' => true
    ]);

    // 2. Expired List
    $expiredList = PriceList::create([
        'name' => 'Expired List', 
        'currency_code' => 'USD', 
        'end_date' => now()->subDay(),
        'is_active' => true
    ]);

    $product = Product::create(['sku' => 'P1', 'product_type' => 'simple', 'name' => 'P1']);

    // Set prices for both lists
    $product->prices()->create(['price_list_id' => $activeList->id, 'price' => 100]);
    $product->prices()->create(['price_list_id' => $expiredList->id, 'price' => 50]);

    $resolver = new PriceResolverService();

    // Check Active List -> Should return 100
    expect($resolver->resolve($product, $activeList->id))->toBe(100.0);

    // Check Expired List -> Should return null
    expect($resolver->resolve($product, $expiredList->id))->toBeNull();
});

it('prioritizes special price over regular price', function () {
    $list = PriceList::create(['name' => 'Values', 'currency_code' => 'USD', 'is_active' => true]);
    $product = Product::create(['sku' => 'P2', 'product_type' => 'simple', 'name' => 'P2']);

    $product->prices()->create([
        'price_list_id' => $list->id, 
        'price' => 100, 
        'special_price' => 80
    ]);

    $resolver = new PriceResolverService();
    expect($resolver->resolve($product, $list->id))->toBe(80.0);
});
