<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

interface PWP_I_Schema_Builder
{
    public function add_int_property(string $name, string $description): PWP_Json_Int_Property;
    public function add_string_property(string $name, string $description): PWP_Json_String_Property;
    public function add_uri_property(string $name, string $description): PWP_Json_String_Property;
    public function add_bool_property(string $name, string $description): PWP_Json_Bool_Property;

    public function add_enum_property(string $name, string $description, array $enumValues): PWP_Json_String_Property;
    public function add_multi_enum_property(string $name, string $description, array $enumValues): PWP_Json_Array_Property;

    public function add_array_property(string $name, string $description): PWP_Json_Array_Property;
    public function add_object_property(string $name, string $description): PWP_Json_Object_Property;
}
