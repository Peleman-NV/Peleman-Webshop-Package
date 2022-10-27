<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

interface I_Schema
{    
    public function add_property(string $name, I_Property $property) : self;
    public function add_properties(array $properties) : self;
    public function to_array(): array;
}
