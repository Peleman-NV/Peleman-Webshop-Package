<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\F2D\PWP_I_Meta_Property;
use WC_Product;

abstract class PWP_Product_Meta_Data implements PWP_I_Meta_Property
{
    protected WC_Product $parent;
    protected array $data;

    public function __construct(\WC_Product $parent)
    {
        $this->parent = $parent;
        $this->data = $parent->get_meta_data();
    }

    final public function get_parent(): \WC_Product
    {
        return $this->parent;
    }

    abstract function update_meta_data(): void;

    public function save(): void
    {
        $this->parent->save();
    }
}
