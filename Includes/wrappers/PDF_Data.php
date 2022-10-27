<?php

declare(strict_types=1);

namespace PWP\includes\wrappers;

class PDF_Data
{
    public function __construct()
    {
    }

    public function get_width(): float
    {
        return $this->width;
    }

    public function get_height(): float
    {
        return $this->height;
    }

    public function get_page_count(): int
    {
        return $this->page_count;
    }
}
