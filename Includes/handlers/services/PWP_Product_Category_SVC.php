<?php

declare(strict_types=1);

namespace PWP\includes\handlers\services;

use PWP\includes\handlers\services\PWP_Term_SVC;

class PWP_Product_Category_SVC extends PWP_Term_SVC
{
    public function __construct(string $sourceLang = 'en')
    {
        parent::__construct(
            'product_cat',
            'tax_product_cat',
            'product category',
            $sourceLang
        );
    }
}
