<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

use PWP\includes\utilities\schemas\I_Property;

abstract class Abstract_Multi_Property extends Abstract_Schema_Property implements I_Property, I_Schema_Builder
{
    protected array $properties;
    private I_Schema_Factory $factory;

    public function __construct(string $description, string $type, I_Schema_Factory $factory, array $args = [])
    {
        $this->factory = $factory;
        $this->properties = array();
        parent::__construct($description, $type, $args);
    }

    public function add_property(string $name, I_Property $property): self
    {
        $this->properties[$name] = $property;
        return $this;
    }

    public function add_properties(array $properties): self
    {
        foreach ($properties as $key => $property) {
            $this->add_property($key, $property);
        }
        return $this;
    }

    #region factory helper methods

    final public function add_int_property(string $name, string $description): Json_Int_Property
    {
        $property = $this->factory->int_property($description);
        $this->add_property($name, $property);
        return $property;
    }

    final public function add_string_property(string $name, string $description): Json_String_Property
    {
        $property = $this->factory->string_property($description);
        $this->add_property($name, $property);
        return $property;
    }

    final public function add_uri_property(string $name, string $description): Json_String_Property
    {
        $property = $this->factory->uri_property($description);
        $this->add_property($name, $property);
        return $property;
    }

    final public function add_bool_property(string $name, string $description): Json_Bool_Property
    {
        $property = $this->factory->bool_property($description);
        $this->add_property($name, $property);
        return $property;
    }

    final public function add_enum_property(string $name, string $description, array $enumValues = []): Json_String_Property
    {
        $property = $this->factory->enum_property($description, $enumValues);
        $this->add_property($name, $property);
        return $property;
    }

    final public function add_multi_enum_property(string $name, string $description, array $enumValues = []): Json_Array_Property
    {
        $property = $this->factory->multi_enum_property($description, $enumValues);
        $this->add_property($name, $property);
        return $property;
    }

    final public function add_array_property(string $name, string $description): Json_Array_Property
    {
        $property = $this->factory->array_property($description);
        $this->add_property($name, $property);
        return $property;
    }

    final public function add_object_property(string $name, string $description): Json_Object_Property
    {
        $property = $this->factory->object_property($description);
        $this->add_property($name, $property);
        return $property;
    }

    #endregion
}
