<?php

declare(strict_types=1);

namespace PWP\includes\wrappers;

class PWP_NullComponent extends PWP_Component
{
    public function __construct()
    {
        parent::__construct([]);
    }
    public function toArray(): array
    {
        return array();
    }
}
