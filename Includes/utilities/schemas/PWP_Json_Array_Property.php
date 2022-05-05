<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

class PWP_Json_Array_Property extends PWP_Abstract_Multi_Property
{
    private array $properties;

    public function __construct(string $description, PWP_I_Schema_Factory $factory, array $args = [])
    {
        parent::__construct($description, 'array', $factory, $args);
        $this->properties = array();
    }

    public function add_properties(array $properties): self
    {
        $this->properties = array_merge($this->properties, $properties);
        return $this;
    }

    public function add_property(string $name, PWP_I_Property $property): self
    {
        $this->properties[$name] = $property;
        return $this;
    }

    public function to_array(): array
    {
        $schema = parent::to_array();

        $array = array('type' => 'object');
        $props = array();
        foreach ($this->properties as $key => $property) {
            $props[$key] = $property->to_array();
        }

        if (!empty($props)) {
            $array['properties'] = $props;
        }
        $schema['items'] = $array;

        return $schema;
    }
}
