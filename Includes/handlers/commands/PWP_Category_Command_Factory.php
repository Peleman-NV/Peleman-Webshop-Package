<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\wrappers\PWP_Term_Data;

final class PWP_Category_Command_Factory extends PWP_Abstract_Term_Command_Factory
{
    public function __construct()
    {
        parent::__construct('product_cat', 'tax_product_cat', "product category");
    }

    final public function new_create_term_command(PWP_Term_Data $data): PWP_Create_Term_Command
    {
        if ($data->has_translation_data()) {
            echo ("creating translated category ");
            return new PWP_Create_Translated_Term_Command($this->service, $data);
        }
        echo ("creating regular category ");
        return new PWP_Create_Term_Command($this->service, $data);
    }

    final public function new_read_term_command(array $args = []): PWP_Read_Term_Command
    {
        return new PWP_Read_Term_Command($this->service, $args);
    }

    final public function new_update_term_command(PWP_Term_Data $data): PWP_Update_Term_Command
    {
        if ($data->has_translation_data()) {
            echo ("updating translated category ");
            return new PWP_Update_Translated_Term_Command($this->service, $data);
        }
        echo ("updating regular category ");
        return new PWP_Update_Term_Command($this->service, $data);
    }

    final public function new_delete_term_command(string $slug): PWP_Delete_Term_Command
    {
        return new PWP_Delete_Term_Command($this->service, $slug);
    }

    final public function new_create_or_update_command(PWP_Term_Data $data): PWP_I_Command
    {
        return $this->slug_exists($data->get_slug(), $data->get_translation_data()->get_language_code() ?: 'en')
            ? $this->new_update_term_command($data)
            : $this->new_create_term_command($data);
    }
}
