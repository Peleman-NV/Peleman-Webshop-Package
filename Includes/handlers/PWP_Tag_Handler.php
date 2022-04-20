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

    public function create_item(array $args = []): object
    {
        //TODO: custom logic for this class
        //product tags do not have parents, so we should be purging that from the args array
        unset($args['parent']);
        
        return parent::create_item($args);
    }
}
