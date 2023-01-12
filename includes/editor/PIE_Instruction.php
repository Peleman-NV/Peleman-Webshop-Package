<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use WC_Product;

final class PIE_Instruction
{
    private string $label;
    private bool $enabled;
    private string $description;

    public function __construct(string $label, bool $enabled = false, string $description = '')
    {
        $this->label = $label;
        $this->enabled = $enabled;
        $this->description = $description;
    }

    public function set_enabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function get_description(): string
    {
        return $this->description;
    }

    public function is_enabled(): bool
    {
        return $this->enabled;
    }

    public function get_label(): string
    {
        return $this->label;
    }
}
