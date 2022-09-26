<?php

declare(strict_types=1);

namespace PWP\includes\wrappers;

class PWP_File_Data extends PWP_Component
{
    private int $pageCount;
    private float $height;
    private float $width;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->pageCount = 0;
        $this->height = 0;
        $this->width = 0;
    }
    public function get_name(): string
    {
        return $this->data->name;
    }

    public function get_type(): string
    {
        return $this->data->type;
    }

    public function get_tmp_name(): string
    {
        return $this->data->tmp_name;
    }

    public function get_error(): int
    {
        return (int)$this->data->error;
    }

    /**
     * return file size in bytes
     *
     * @return integer
     */
    public function get_size(): int
    {
        return (int)$this->data->size;
    }

    public function set_page_count(int $pages): void
    {
        $this->pageCount = $pages;
    }

    public function get_page_count(): int
    {
        return $this->pageCount;
    }

    public function set_dimensions(float $width, float $height): void
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function get_height(): float
    {
        return $this->height;
    }

    public function get_width(): float
    {
        return $this->width;
    }
}
