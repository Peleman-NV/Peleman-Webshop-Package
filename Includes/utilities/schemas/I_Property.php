<?php

declare(strict_types=1);

namespace PWP\includes\utilities\schemas;

interface I_Property
{
    public function is_required(): bool;
    public function to_array(): array;

    public function validate_callback(string $callback): self;
    public function santize_callback(string $callback): self;

    public function add_custom_arg(string $key, $arg): self;
}
