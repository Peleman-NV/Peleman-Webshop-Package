<?php

declare(strict_types=1);

namespace PWP\includes\utilities;

class PWP_Json_Array_property extends PWP_Json_Schema_Property
{
    private array $properties;

    public function __construct(string $domain, string $description, array $args = [])
    {
        parent::__construct($domain, $description, 'array', $args);
    }

    public function add_property(string $name, PWP_Json_Schema_Property $property)
    {
        $this->properties[$name] = $property;
    }
}
