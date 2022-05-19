<?php

declare(strict_types=1);

namespace PWP\includes\wrappers;

class PWP_File_Data extends PWP_Component
{
    public function get_name(): string
    {
        return $this->data->name;
    }

    public function get_type(): string
    {
        return $this->data->type;
    }

    public function get_tmp_name(): string
    {
        return $this->data->tmp_name;
    }

    public function get_error(): int
    {
        return (int)$this->data->error;
    }
}
