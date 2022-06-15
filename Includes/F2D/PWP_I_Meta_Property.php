<?php

declare(strict_types=1);

namespace PWP\includes\F2D;

interface PWP_I_Meta_Property
{
    /**
     * Undocumented function
     *
     * @return mixed
     */

    public function get_value(): mixed;
    /**
     * Undocumented function
     *
     * @param string $value
     * @return mixed
     */
    public function set_value(string $value);

    /**
     * Undocumented function
     *
     * @return string
     */
    public function get_key(): string;
}
