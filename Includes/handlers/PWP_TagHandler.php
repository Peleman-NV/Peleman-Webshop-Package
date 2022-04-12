<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use PWP\includes\PWP_ArgBuilder;
use stdClass;

class PWP_TagHandler extends PWP_TermHandler
{
    protected string $myType = 'product_tag';
    protected string $myTypeLong = 'product_tag';

    protected function __construct()
    {
        $this->myType = 'product_tag';
        $this->myTypeLong = 'product_tag';
    }
    public function create(string $name, string $slug, string $parent = '', array $args = []): void
    {
        $argBuilder = new PWP_ArgBuilder($args);

        if ($parent !== '') {
            $argBuilder->add_arg_if_not_null('parent', $this->find_parent_by_slug($slug));
        }
        $this->add_new_term($name, $this->myType, $argBuilder->to_array());
    }

    public function update(stdClass $itemData): void
    {
    }
}
