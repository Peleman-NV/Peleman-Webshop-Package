<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use PWP\includes\utilities\PWP_ILogger;
use WP_Term;

class PWP_Category_Handler extends PWP_Term_Handler
{
    public function __construct(PWP_ILogger $logger)
    {
        parent::__construct('product_cat', 'product category', $logger);
    }
}
