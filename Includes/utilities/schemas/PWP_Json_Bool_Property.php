<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

final class PWP_Json_Bool_Property extends PWP_Json_Schema_Property
{
    public function __construct(string $domain, string $description, array $args = [])
    {
        parent::__construct($domain, $description, 'boolean', $args);
    }

    public function default(bool $default): PWP_Json_Schema_Property
    {
        $this->default = $default;
        return $this;
    }
}
