<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use WP_Term;
use PWP\includes\utilities\PWP_ILogger;
use PWP\includes\wrappers\PWP_Category;

class PWP_Category_Handler extends PWP_Term_Handler
{
    public function __construct(PWP_ILogger $logger)
    {
        parent::__construct('product_cat', 'product category', 'tax_product_cat', $logger);
    }
}
