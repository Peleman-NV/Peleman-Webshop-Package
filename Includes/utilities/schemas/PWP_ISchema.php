<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

interface PWP_ISchema
{    
    public function add_property(string $name, PWP_IProperty $property) : PWP_ISchema;
    public function add_properties(array $properties) : PWP_ISchema;
    public function to_array(): array;
}
