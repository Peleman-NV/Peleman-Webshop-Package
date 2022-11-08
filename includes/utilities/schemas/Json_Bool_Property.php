<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

final class Json_Bool_Property extends Abstract_Schema_Property
{
    public function __construct(string $description, array $args = [])
    {
        parent::__construct($description, 'boolean', $args);
    }

    public function default(bool $default): Abstract_Schema_Property
    {
        $this->default = $default;
        return $this;
    }
}
