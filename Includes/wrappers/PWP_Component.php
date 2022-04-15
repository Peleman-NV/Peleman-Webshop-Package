<?php

declare(strict_types=1);

namespace PWP\includes\wrappers;

abstract class PWP_Component
{
    protected object $data;
    private static PWP_NullComponent $nullInstance;

    public function __construct(?array $data)
    {
        $this->data = (object)$data;
    }

    public function toArray(): array
    {
        return (array)$this->data;
    }

    public static function null(): PWP_Component
    {
        if (!isset(self::$nullInstance)) {
            self::$nullInstance = new PWP_NullComponent();
        }
        return self::$nullInstance;
    }
}
