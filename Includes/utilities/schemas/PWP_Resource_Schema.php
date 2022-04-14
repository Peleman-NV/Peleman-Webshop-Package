<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

class PWP_Resource_Schema implements PWP_ISchema
{
    private array $header;
    private array $properties;
    private array $required;

    public function __construct(string $title)
    {
        $this->header = array(
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => $title,
            'type'       => 'object',
            'properties' => array(),
            'required' => array(),
        );

        $this->properties = array();
        $this->required = array();
    }

    public function add_property(string $name, PWP_IProperty $property): PWP_Resource_Schema
    {
        $this->properties[$name] = $property;

        if ($property->is_required()) {
            $this->required[] = $name;
        }

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param PWP_IProperty[] $properties
     * @return PWP_Resource_Schema
     */
    public function add_properties(array $properties): PWP_Resource_Schema
    {
        foreach ($properties as $key => $property) {
            $this->add_property($key, $property);
        }
        return $this;
    }

    public function to_array(): array
    {
        $schema = $this->header;
        $schema['properties'] = array_map(function ($e) {
            return $e->to_array();
        }, $this->properties);

        $schema['required'] = $this->required;
        return $schema;
    }

    public function get_properties(): array
    {
        return $this->properties;
    }
}
