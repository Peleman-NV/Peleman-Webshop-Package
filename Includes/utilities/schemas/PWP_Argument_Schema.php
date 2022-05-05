<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

class PWP_Argument_Schema implements PWP_ISchema
{
    private array $properties;
    private PWP_I_Schema_Factory $factory;

    #region schema methods
    public function __construct(PWP_I_Schema_Factory $factory)
    {
        $this->factory = $factory;
        $this->properties = array();
    }

    public function add_property(string $name, PWP_IProperty $property): self
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

    public function to_array(): array
    {
        return array_map(function ($e) {
            return $e->to_array();
        }, $this->properties);
    }

    #endregion

    #region factory helper methods

    final public function add_int_property(string $name, string $description): PWP_Json_int_Property
    {
        $property = $this->factory->int_property($description);
        $this->add_property($name, $property);
        return $property;
    }

    final public function add_string_property(string $name, string $description): PWP_Json_String_Property
    {
        $property = $this->factory->string_property($description);
        $this->add_property($name, $property);
        return $property;
    }

    final public function add_uri_property(string $name, string $description): PWP_Json_String_Property
    {
        $property = $this->factory->uri_property($description);
        $this->add_property($name, $property);
        return $property;
    }

    final public function add_bool_property(string $name, string $description): PWP_Json_Bool_Property
    {
        $property = $this->factory->bool_property($description);
        $this->add_property($name, $property);
        return $property;
    }

    final public function add_enum_property(string $name, string $description, array $enumValues = []): PWP_Json_String_Property
    {
        $property = $this->factory->enum_property($description, $enumValues);
        $this->add_property($name, $property);
        return $property;
    }

    final public function add_multi_enum_property(string $name, string $description, array $enumValues = []): PWP_Json_Array_Property
    {
        $property = $this->factory->multi_enum_property($description, $enumValues);
        $this->add_property($name, $property);
        return $property;
    }

    final public function add_array_property(string $name, string $description): PWP_Json_Array_Property
    {
        $property = $this->factory->array_property($description);
        $this->add_property($name, $property);
        return $property;
    }

    final public function add_object_property(string $name, string $description): PWP_Json_Object_Property
    {
        $property = $this->factory->object_property($description);
        $this->add_property($name, $property);
        return $property;
    }

    #endregion
}
