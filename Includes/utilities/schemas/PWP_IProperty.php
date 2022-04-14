<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

interface PWP_IProperty
{
    public function is_required(): bool;
    public function to_array(): array;

    public function validate_callback(string $callback): PWP_IProperty;
    public function santize_callback(string $callback): PWP_IProperty;

    public function add_custom_arg(string $key, $arg): PWP_IProperty;
}
