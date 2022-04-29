<?php

declare(strict_types=1);

namespace PWP\includes\wrappers;

class PWP_Translation_Data extends PWP_Component
{
    final public function get_english_slug(): string
    {
        return $this->data->english_slug ?: '';
    }

    final public function get_language_code(): string
    {
        return $this->data->language_code ?: '';
    }

    final public function is_valid_data(): bool
    {
        return ($this->data->english_slug || $this->data->language_code);
    }
}
