<?php

namespace Obelaw\Pim\Services;

use Obelaw\Pim\Interfaces\StockManagerInterface;
use Obelaw\Pim\Managers\PimStockManager;
use Obelaw\Pim\Models\Product;
use Obelaw\Pim\Models\ProductVariant;
use Obelaw\Pim\Repositories\CategoryRepository;
use Obelaw\Pim\Repositories\ProductRepository;

/**
 * use proxy pattern
 */
class PIMService
{
    protected array $configs = [
        'default_stock_manager' => PimStockManager::class,
    ];

    public function init($configs = []): self
    {
        $this->configs = array_merge($this->configs, $configs);

        app()->bind(StockManagerInterface::class, $this->configs['default_stock_manager']);

        return $this;
    }

    public function products(): ProductRepository
    {
        return app(ProductRepository::class);
    }

    public function categories(): CategoryRepository
    {
        return app(CategoryRepository::class);
    }

    public function stockManager(?StockManagerInterface $stockManager = null): StockManagerInterface
    {
        if ($stockManager) {
            return $stockManager;
        }

        return app($this->configs['default_stock_manager']);
    }

    public function uomConverter(): UomConverter
    {
        return new UomConverter();
    }
}
