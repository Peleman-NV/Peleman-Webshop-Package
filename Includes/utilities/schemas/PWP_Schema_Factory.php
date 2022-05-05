<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

class PWP_Schema_Factory implements PWP_I_Schema_Factory
{
    private string $domain;
    public function __construct($domain = 'default')
    {
        $this->domain = $domain;
    }

    public function int_property(string $description): PWP_Json_int_Property
    {
        return new PWP_Json_int_Property(
            __($description, $this->domain),
        );
    }

    public function string_property(string $description): PWP_Json_String_Property
    {
        return new PWP_Json_String_Property(
            __($description, $this->domain),
        );
    }

    public function uri_property(string $description): PWP_Json_String_Property
    {
        return  $this->string_property($description)
            ->add_custom_arg('format', 'uri');
    }

    public function bool_property(string $description): PWP_Json_Bool_Property
    {
        return new PWP_Json_Bool_Property(
            __($description, $this->domain),
        );
    }

    /**
     * enum property, accepts a single string value to match against
     *
     * @param string $description
     * @param array $enumValues
     * @return PWP_Abstract_Schema_Property
     */
    public function enum_property(string $description, array $enumValues): PWP_Json_String_Property
    {
        return new PWP_Json_String_Property(
            __($description, $this->domain),
            array('enum' => $enumValues)
        );
    }

    /**
     * multi enum property, accepts one or multiple values to match against
     *
     * @param string $description
     * @param array $enumValues
     * @return PWP_Json_Array_property
     */
    public function multi_enum_property(string $description, array $enumValues, array $args = []): PWP_Json_Array_property
    {
        return new PWP_Json_Array_property(
            __($description, $this->domain),
            $this->factory,
            array('enum' => $enumValues),
            $args
        );
    }

    public function array_property(string $description, array $args = []): PWP_Json_Array_property
    {
        return new PWP_Json_Array_property(
            __($description, $this->domain),
            $this->factory,
            $args
        );
    }

    public function object_property(string $description): PWP_Json_Object_Property
    {
        return new PWP_Json_Object_Property(
            __($description, $this->domain)
        );
    }
}
