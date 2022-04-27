<?php

declare(strict_types=1);

namespace PWP\includes\wrappers;

class PWP_SEO_Data extends PWP_Component
{
    public function get_description(): string
    {
        return $this->data->description ?: '';
    }

    public function get_focus_keyword(): string
    {
        return $this->data->focus_keyword ?: '';
    }
}
