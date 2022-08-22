<?php

declare(strict_types=1);

namespace PWP\includes\wrappers;

class PWP_Product_Meta_Data extends PWP_Component
{
    private int $productId;

    public function __construct(int $productId, array $data = [])
    {
        $this->productId = $productId;
        parent::__construct($data);
    }

    public function get_id(): int
    {
        return $this->productId;
    }
}
