<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

abstract class Schema
{
    protected array $schema;

    public function to_array(): array
    {
        return $this->schema;
    }
}
