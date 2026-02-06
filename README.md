# Obelaw PIM

A Product Information Management (PIM) package for Laravel.

## Installation

You can install the package via composer:

```bash
composer require obelaw/pim
```

## Usage

You can use the `PIM` facade to manage products.

### Creating a Simple Product

To create a simple product, use `ProductDTO` with `productType` set to `'simple'`.

```php
use Obelaw\Pim\Facades\PIM;
use Obelaw\Pim\Data\ProductDTO;

$simpleProductData = new ProductDTO(
    sku: 'TSHIRT-BLUE-M',
    name: 'Blue T-Shirt Medium',
    productType: 'simple',
    price: 19.99,
    description: 'A comfortable blue cotton t-shirt.'
);

$product = PIM::createProduct($simpleProductData);
```

### Creating a Product with Categories

You can assign categories to a product by passing an array of category IDs to the `categories` parameter in `ProductDTO`.

```php
use Obelaw\Pim\Facades\PIM;
use Obelaw\Pim\Data\ProductDTO;

$productData = new ProductDTO(
    sku: 'SMART-WATCH-001',
    name: 'Smart Watch',
    productType: 'simple',
    price: 199.99,
    categories: [1, 5] // Array of Category IDs
);

$product = PIM::createProduct($productData);
```

### Creating a Configurable Product

To create a configurable product (e.g., a T-Shirt with multiple sizes/colors), set `productType` to `'configurable'` and provide an array of `VariantDTO` objects.

```php
use Obelaw\Pim\Facades\PIM;
use Obelaw\Pim\Data\ProductDTO;
use Obelaw\Pim\Data\VariantDTO;

$configurableProductData = new ProductDTO(
    sku: 'TSHIRT-BASE',
    name: 'T-Shirt',
    productType: 'configurable',
    price: 19.99,
    description: 'A basic t-shirt available in multiple sizes.',
    variants: [
        new VariantDTO(
            sku: 'TSHIRT-S',
            price: 19.99,
            stock: 10,
            attributes: [/* Attribute IDs if applicable */]
        ),
        new VariantDTO(
            sku: 'TSHIRT-M',
            price: 19.99,
            stock: 15
        ),
        new VariantDTO(
            sku: 'TSHIRT-L',
            price: 21.99, // Different price for Large
            stock: 5
        ),
    ]
);

$product = PIM::createProduct($configurableProductData);
```

### Retrieving a Product

```php
use Obelaw\Pim\Facades\PIM;

$productResource = PIM::getProduct($id);
```

## Testing

Run the tests with:

```bash
composer test
```
