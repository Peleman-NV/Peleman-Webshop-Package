<?php

declare(strict_types=1);

namespace PWP\includes\handlers\services;

use PWP\includes\exceptions\Not_Implemented_Exception;

class Product_SVC implements I_SVC
{
    public function get_item_by_id(int $id): object
    {
        throw new Not_Implemented_Exception(__METHOD__);
    }

    public function get_item_by_name(string $name): object
    {
        throw new Not_Implemented_Exception(__METHOD__);
    }

    public function get_item_by_slug(string $slug): object
    {
        throw new Not_Implemented_Exception(__METHOD__);
    }

    public function get_item_by_sku(string $sku): object
    {
        throw new Not_Implemented_Exception(__METHOD__);
    }
}
