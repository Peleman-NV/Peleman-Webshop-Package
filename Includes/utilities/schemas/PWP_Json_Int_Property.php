<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

final class PWP_Json_int_Property extends PWP_Abstract_Schema_Property
{
    public function __construct(string $description, array $args = [])
    {
        parent::__construct($description, 'integer', $args);
    }
    
    public function default(int $default): PWP_Abstract_Schema_Property
    {
        $this->default = $default;
        return $this;
    }
}
