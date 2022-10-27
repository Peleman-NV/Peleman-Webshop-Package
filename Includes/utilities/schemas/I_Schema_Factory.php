<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

interface I_Schema_Factory
{
    public function int_property(string $description): Json_Int_Property;
    public function string_property(string $description): Json_String_Property;
    public function uri_property(string $description): Json_String_Property;
    public function bool_property(string $description): Json_Bool_Property;
    public function enum_property(string $description, array $enumValues): Json_String_Property;

    public function multi_enum_property(string $description, array $enumValues): Json_Array_Property;
    public function array_property(string $description): Json_Array_Property;

    public function object_property(string $description): Json_Object_Property;
}
