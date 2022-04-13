<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use WP_Error;
use WP_Term;

class PWP_Tag_Handler extends PWP_Term_Handler
{
    public function __construct()
    {
        parent::__construct('product_tag', 'product tag');
    }

    public function get_item(int $id, array $args = []): WP_Term
    {
        $term = get_term($id, $this->taxonomy, OBJECT);
        return $term;
    }

    public function get_items(array $args = []): array
    {
        $terms = get_terms($this->taxonomy);
        return $terms;
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

    public function delete_item(int $id, array $args = []): bool
    {
        //TODO: implementation
        throw new \Exception("not implemented", 501);
        throw new \Exception
    }
}
