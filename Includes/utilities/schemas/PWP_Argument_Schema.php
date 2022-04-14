<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

class PWP_Argument_Schema implements PWP_ISchema
{
    private array $properties;

    public function __construct()
    {
        $this->properties = array();
    }

    public function add_property(string $name, PWP_IProperty $property): PWP_Argument_Schema
    {
        $this->properties[$name] = $property;
        return $this;
    }

    public function add_properties(array $properties): PWP_Argument_Schema
    {
        foreach ($properties as $key => $property) {
            $this->add_property($key, $property);
        }
        return $this;
    }

    public function to_array(): array
    {
        return array_map(function ($e) {
            return $e->to_array();
        }, $this->properties);
    }
}
