<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use PWP\includes\utilities\PWP_ILogger;
use PWP\includes\wrappers\PWP_Category;

class PWP_Tag_Handler extends PWP_Term_Handler
{
    public function __construct(PWP_ILogger $logger)
    {
        parent::__construct('product_tag', 'product tag', 'tax_product_tag', $logger);
    }

    public function create_item(string $identifier, array $args = []): \WP_Term
    {
        //TODO: custom logic for this class
        //product tags do not have parents, so we should be purging that from the args array
        unset($args['parent']);

        return parent::create_item($identifier, $args);
    }
}
