<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

final class Json_Int_Property extends Abstract_Schema_Property
{
    public function __construct(string $description, array $args = [])
    {
        parent::__construct($description, 'integer', $args);
    }
    
    public function default(int $default): Abstract_Schema_Property
    {
        $this->default = $default;
        return $this;
    }
}
