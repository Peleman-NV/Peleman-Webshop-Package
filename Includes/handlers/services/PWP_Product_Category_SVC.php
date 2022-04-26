<?php

declare(strict_types=1);

namespace PWP\includes\handlers\services;

class PWP_Product_Category_SVC extends PWP_Term_SVC
{
    public function __construct()
    {
        parent::__construct('product_cat', 'tax_product_cat', 'product category');
    }
}
