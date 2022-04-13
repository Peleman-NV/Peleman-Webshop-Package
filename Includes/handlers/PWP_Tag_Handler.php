<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use WP_Term;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;

class PWP_Tag_Handler extends PWP_Term_Handler
{
    public function __construct()
    {
        parent::__construct('product_tag', 'product tag');
    }

    public function create_item(array $data, array $args = []): WP_Term
    {
        //TODO: implementation
        throw new PWP_Not_Implemented_Exception("not implemented");
    }

    public function update_item(int $id, array $args = []): WP_Term
    {
        //TODO: implementation
        throw new PWP_Not_Implemented_Exception("not implemented");
    }
}
