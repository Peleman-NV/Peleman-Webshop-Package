<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use WP_Term;

class PWP_Category_Handler extends PWP_Term_Handler
{
    public function __construct()
    {
        parent::__construct('product_cat', 'product category');
    }
}
