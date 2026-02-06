<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Obelaw\Pim\Models\Category;
use Obelaw\Pim\Tests\MigrationBaseTest;

uses(RefreshDatabase::class);

it('can create a category', function () {
    $category = Category::create([
        'name' => 'Electronics',
        'slug' => 'electronics',
        'is_active' => true,
    ]);

    expect($category)->toBeInstanceOf(Category::class)
        ->name->toBe('Electronics')
        ->slug->toBe('electronics')
        ->is_active->toBeTrue();

    $this->assertDatabaseHas((new MigrationBaseTest)->getPrefix() . 'categories', [
        'name' => 'Electronics',
        'slug' => 'electronics',
    ]);
});

it('can create a category hierarchy', function () {
    $parent = Category::create([
        'name' => 'Electronics',
        'slug' => 'electronics',
    ]);

    $child = Category::create([
        'name' => 'Computers',
        'slug' => 'computers',
        'parent_id' => $parent->id,
    ]);

    expect($child->parent)->toBeInstanceOf(Category::class)
        ->id->toBe($parent->id);

    expect($parent->children)->toHaveCount(1)
        ->first()->id->toBe($child->id);
});
