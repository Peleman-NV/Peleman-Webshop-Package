<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

class Resource_Schema extends Argument_Schema
{
    private array $header;
    private array $required;

    public function __construct(I_Schema_Factory $factory, string $title)
    {
        parent::__construct($factory);

        $this->header = array(
            '$schema'       => 'http://json-schema.org/draft-04/schema#',
            'title'         => $title,
            'type'          => 'object',
            'properties'    => array(),
            'required'      => array(),
        );

        $this->properties = array();
        $this->required = array();
    }

    public function add_property(string $name, I_Property $property): self
    {
        parent::add_property($name, $property);

        if ($property->is_required()) {
            $this->required[] = $name;
        }

        return $this;
    }

    public function to_array(): array
    {
        $schema = $this->header;
        // $schema['properties'] = parent::to_array();
        // $schema['required'] = $this->required;
        return $schema;
    }

    final public function get_properties(): array
    {
        return $this->properties;
    }
}
