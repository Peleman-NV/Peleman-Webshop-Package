<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use Requests_Exception_HTTP_404;
use WP_Term;

class PWP_Tag_Handler extends PWP_Term_Handler
{
    public function __construct()
    {
        parent::__construct('product_tag','product tag');
    }

    public function get_tags() : array
    {
        $terms = get_terms($this->taxonomy);
        return $terms;
    }

    public function get_tag(int $id) : WP_Term
    {
        $term = get_term($id, $this->taxonomy, OBJECT);
        return $term;
    }
}
