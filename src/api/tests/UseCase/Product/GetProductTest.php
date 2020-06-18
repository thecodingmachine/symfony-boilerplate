<?php

declare(strict_types=1);

use App\Domain\Throwable\NotFound\ProductNotFoundById;
use App\UseCase\Company\CreateCompany;
use App\UseCase\Product\CreateProduct;
use App\UseCase\Product\GetProduct;

it(
    'gets a product',
    function (): void {
        $createCompany = self::$container->get(CreateCompany::class);
        $createProduct = self::$container->get(CreateProduct::class);
        $getProduct    = self::$container->get(GetProduct::class);
        assert($createCompany instanceof CreateCompany);
        assert($createProduct instanceof CreateProduct);
        assert($getProduct instanceof GetProduct);

        $company = $createCompany->createCompany('foo');
        $product = $createProduct->create('foo', 1, $company->getId());

        $foundProduct = $getProduct->getProductById($product->getId());
        assertEquals($product->getId(), $foundProduct->getId());
    }
);

it(
    'throws an exception if invalid id.',
    function (): void {
        $getProduct = self::$container->get(GetProduct::class);
        assert($getProduct instanceof GetProduct);

        $getProduct->getProductById('foo');
    }
)
    ->throws(ProductNotFoundById::class);
