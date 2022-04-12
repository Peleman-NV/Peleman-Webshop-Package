<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use stdClass;

class PWP_CategoryHandler extends PWP_TermHandler
{
    public function __construct()
    {
        $this->myType = 'product_cat';
        $this->myTypeLong = 'product category';
    }

    public function create(string $name, string $slug, string $parent = '', array $args = []): void
    {
        $this->add_new_term($name, $this->myType, $args);
    }

    public function update(stdClass $itemData): void
    {
    }
}
