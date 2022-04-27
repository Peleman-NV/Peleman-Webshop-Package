<?php

declare(strict_types=1);

namespace PWP\includes\wrappers;

abstract class PWP_Component implements PWP_I_Component
{
    protected object $data;

    public function __construct(?array $data)
    {
        $this->data = (object)$data;
    }

    public function to_array(): array
    {
        return (array)$this->data;
    }
}
