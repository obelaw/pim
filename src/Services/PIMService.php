<?php

namespace Obelaw\Pim\Services;

use Obelaw\Pim\Data\ProductDTO;
use Obelaw\Pim\Repositories\ProductRepository;
use Obelaw\Pim\Resources\ProductResource;
use Obelaw\Pim\Services\UomConverter;

/**
 * use proxy pattern
 */
class PIMService
{
    public function __construct(
        protected ProductRepository $productRepository
    ) {}

    public function createProduct(ProductDTO $data)
    {
        return $this->productRepository->create($data);
    }

    public function getProduct(int $id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return null;
        }

        return new ProductResource($product);
    }

    public function uomConverter(): UomConverter
    {
        return new UomConverter();
    }
}
