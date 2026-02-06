<?php

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Obelaw\Pim\Data\MediaDTO;
use Obelaw\Pim\Data\ProductDTO;
use Obelaw\Pim\Models\Category;
use Obelaw\Pim\Models\Product;
use Obelaw\Pim\Repositories\ProductRepository;
use Obelaw\Pim\Tests\MigrationBaseTest;

uses(RefreshDatabase::class);

it('can create a simple product', function () {
    $repository = new ProductRepository();

    $dto = new ProductDTO(
        sku: 'SKU-001',
        name: 'Test Product',
        productType: 'simple',
        price: 100.00,
        description: 'A test product'
    );

    $product = $repository->create($dto);

    expect($product)->toBeInstanceOf(Product::class)
        ->sku->toBe('SKU-001')
        ->name->toBe('Test Product')
        ->product_type->toBe('simple')
        ->price->toBe(100.00);
 
    $this->assertDatabaseHas((new MigrationBaseTest)->getPrefix() . 'products', [
        'sku' => 'SKU-001',
        'name' => 'Test Product',
    ]);
});

it('throws exception when creation fails', function () {
    $repository = new ProductRepository();

    $dto1 = new ProductDTO(
        sku: 'SKU-DUPLICATE',
        name: 'Product 1',
        productType: 'simple',
        price: 100.00
    );

    $repository->create($dto1);


    $dto2 = new ProductDTO(
        sku: 'SKU-DUPLICATE',
        name: 'Product 2',
        productType: 'simple',
        price: 200.00
    );

    expect(fn() => $repository->create($dto2))
        ->toThrow(Exception::class, 'Failed to create product:');
});

it('can create a product with categories', function () {
    $parent = Category::create([
        'name' => 'Electronics',
        'slug' => 'electronics',
    ]);
    $child = Category::create([
        'name' => 'Laptops',
        'slug' => 'laptops',
        'parent_id' => $parent->id,
    ]);

    $repository = new ProductRepository();

    $dto = new ProductDTO(
        sku: 'LAPTOP-001',
        name: 'Gaming Laptop',
        productType: 'simple',
        price: 1500.00,
        categories: [$parent->id, $child->id]
    );

    $product = $repository->create($dto);

    expect($product->categories)->toHaveCount(2);
    expect($product->categories->pluck('id')->toArray())
        ->toContain($parent->id)
        ->toContain($child->id);

    $this->assertDatabaseHas((new MigrationBaseTest)->getPrefix() . 'category_product', [
        'product_id' => $product->id,
        'category_id' => $parent->id,
    ]);
});
