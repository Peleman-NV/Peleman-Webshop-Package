<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\wrappers\PWP_Term_Data;

class PWP_Tag_Handler extends PWP_Term_Handler
{
    public function __construct()
    {
        parent::__construct(new PWP_Term_SVC('product_tag', 'tax_product_tag', 'product tag'));
    }

    public function create_item(array $createData, array $args = []): \WP_Term
    {
        //TODO: custom logic for this class
        //product tags do not have parents, so we should be purging that from the args array
        $createData['parent_id'] = 0;
        $createData['parent_slug'] = ('');

        return parent::create_item($createData, $args);
    }
}
