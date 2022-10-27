<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

class Schema_Factory implements I_Schema_Factory
{
    private string $domain;
    public function __construct($domain = 'default')
    {
        $this->domain = $domain;
    }

    public function int_property(string $description): Json_Int_Property
    {
        return new Json_Int_Property(
            __($description, $this->domain),
        );
    }

    public function string_property(string $description): Json_String_Property
    {
        return new Json_String_Property(
            __($description, $this->domain),
        );
    }

    public function uri_property(string $description): Json_String_Property
    {
        return  $this->string_property($description)
            ->add_custom_arg('format', 'uri');
    }

    public function bool_property(string $description): Json_Bool_Property
    {
        return new Json_Bool_Property(
            __($description, $this->domain),
        );
    }

    /**
     * enum property, accepts a single string value to match against
     *
     * @param string $description
     * @param array $enumValues
     * @return Abstract_Schema_Property
     */
    public function enum_property(string $description, array $enumValues): Json_String_Property
    {
        return new Json_String_Property(
            __($description, $this->domain),
            array('enum' => $enumValues)
        );
    }

    /**
     * multi enum property, accepts one or multiple values to match against
     *
     * @param string $description
     * @param array $enumValues
     * @return Json_Array_Property
     */
    public function multi_enum_property(string $description, array $enumValues, array $args = []): Json_Array_Property
    {
        return new Json_Array_Property(
            __($description, $this->domain),
            $this,
            array('enum' => $enumValues),
            $args
        );
    }

    public function array_property(string $description, array $args = []): Json_Array_Property
    {
        return new Json_Array_Property(
            __($description, $this->domain),
            $this,
            $args
        );
    }

    public function object_property(string $description): Json_Object_Property
    {
        return new Json_Object_Property(
            __($description, $this->domain)
        );
    }
}
