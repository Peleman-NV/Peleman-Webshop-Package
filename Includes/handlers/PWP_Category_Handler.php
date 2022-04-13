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

    public function create_item(array $data, array $args = []): WP_Term
    {
        //TODO: implementation
        throw new \Exception("not implemented", 501);
    }

    public function update_item(int $id, array $args = []): WP_Term
    {
        //TODO: implementation
        throw new \Exception("not implemented", 501);
    }
}
