<?php

declare(strict_types=1);

namespace PWP\includes\wrappers;

use WC_Product;

class PWP_Product_Meta_Data extends PWP_Component
{
    private int $productId;
    public function __construct(int $productId, array $data = [])
    {
        $this->productId = $productId;
        parent::__construct($data);

        // $this->productId = $product->get_id();
        // parent::__construct($product->get_meta());
    }

    public function get_id(): int
    {
        return $this->productId;
    }
    
    public function get_pdf_width(): ?int
    {
        return $this->data->pdf_width_mm;
    }

    public function get_pdf_height(): ?int
    {
        return $this->data->pdf_height_mm;
    }

    public function get_pdf_min_pages(): ?int
    {
        return $this->data->pdf_min_pages;
    }

    public function get_pdf_max_pages(): ?int
    {
        return $this->data->pdf_max_pages;
    }

    public function get_price_per_page(): ?float
    {
        return $this->data->price_per_page;
    }
}
