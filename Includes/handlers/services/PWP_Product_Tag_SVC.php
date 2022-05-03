<?php

declare(strict_types=1);

namespace PWP\includes\handlers\services;

use PWP\includes\handlers\services\PWP_Term_SVC;

class PWP_Product_Tag_SVC extends PWP_Term_SVC
{
    public function __construct(string $sourceLang = 'en')
    {
        parent::__construct(
            'product_tag',
            'tax_product_tag',
            'product tag',
            $sourceLang
        );
    }
}
