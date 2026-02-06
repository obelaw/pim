<?php

namespace Obelaw\Pim\Services;

use Obelaw\Pim\Repositories\CategoryRepository;
use Obelaw\Pim\Repositories\ProductRepository;

/**
 * use proxy pattern
 */
class PIMService
{
    public function products(): ProductRepository
    {
        return app(ProductRepository::class);
    }

    public function categories(): CategoryRepository
    {
        return app(CategoryRepository::class);
    }

    public function uomConverter(): UomConverter
    {
        return new UomConverter();
    }
}
