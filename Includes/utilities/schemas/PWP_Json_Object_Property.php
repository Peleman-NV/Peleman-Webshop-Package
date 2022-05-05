<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

final class PWP_Json_Object_Property extends PWP_Json_Schema_Property
{
    private array $properties;

    public function __construct(string $domain, string $description, array $args = [])
    {
        parent::__construct($domain, $description, 'object', $args);
        $this->properties = array();
    }

    public function add_properties(array $properties): self
    {
        $this->properties = array_merge($this->properties, $properties);
        return $this;
    }

    public function add_property(string $name, PWP_IProperty $property): self
    {
        $this->properties[$name] = $property;
        return $this;
    }

    public function to_array(): array
    {
        $schema = parent::to_array();

        $object = array('type' => $this->type);
        $props = array();
        foreach ($this->properties as $key => $property) {
            $props[$key] = $property->to_array();
        }

        if (!empty($props)) {
            $array['properties'] = $props;
        }
        $schema['items'] = $object;

        return $schema;
    }
}
