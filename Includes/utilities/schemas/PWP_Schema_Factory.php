<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

class PWP_Schema_Factory
{
    private string $domain;
    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    public function int_property(string $description): PWP_Json_Schema_Property
    {
        return new PWP_Json_Schema_Property(
            $this->domain,
            $description,
            'integer'
        );
    }

    public function string_property(string $description): PWP_Json_Schema_Property
    {
        return new PWP_Json_Schema_Property(
            $this->domain,
            $description,
            'string'
        );
    }

    public function uri_property(string $description): PWP_Json_Schema_Property
    {
        return  $this->string_property($description)
            ->add_custom_arg('format', 'uri');
    }

    public function bool_property(string $description): PWP_Json_Schema_Property
    {
        return new PWP_Json_Schema_Property(
            $this->domain,
            $description,
            'boolean'
        );
    }

    /**
     * enum property, accepts a single string value to match against
     *
     * @param string $description
     * @param array $enumValues
     * @return PWP_Json_Schema_Property
     */
    public function enum_property(string $description, array $enumValues): PWP_Json_Schema_Property
    {
        return new PWP_Json_Schema_Property(
            $this->domain,
            $description,
            'string',
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
    public function multi_enum_property(string $description, array $enumValues): PWP_Json_Array_property
    {
        return new PWP_Json_Array_property(
            $this->domain,
            $description,
            array('enum' => $enumValues)
        );
    }

    public function array_property(string $description): PWP_Json_Array_property
    {
        return new PWP_Json_Array_property(
            $this->domain,
            $description,
        );
    }
}
