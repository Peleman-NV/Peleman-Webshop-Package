<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\exceptions\PWP_Resource_Already_Exists_Exception;
use PWP\includes\validation\PWP_Validate_Term_Unique_Slug;

final class PWP_Category_Command_Factory extends PWP_Abstract_Term_Command_Factory
{
    public function __construct()
    {
        parent::__construct('product_cat', 'tax_product_cat', "product category");
    }

    final public function new_create_term_command(PWP_Term_Data $data): PWP_Create_Term_Command
    {
        $handler = new PWP_Validate_Term_Unique_Slug($this->get_service());
        $handler->handle($data);
        if ($this->get_service()->get_item_by_slug($this->slug)) {
            throw new PWP_Resource_Already_Exists_Exception("{$this->get_service()->get_beauty_name()} with the slug {$this->slug} already exists. Slugs should be unique to avoid confusion.");
        }

        if ($data->has_translation_data()) {
            return new PWP_Create_Translated_Term_Command($this->handler, $data->get_slug(), $data);
        }
        return new PWP_Create_Term_Command($this->handler, $data->get_slug(), $data);
    }

    final public function new_read_term_command(array $args = []): PWP_Read_Term_Command
    {
        return new PWP_Read_Term_Command($this->get_service(), $args);
    }

    final public function new_update_term_command(PWP_Term_Data $data): PWP_Update_Term_Command
    {
        echo "hi ";
        if ($data->has_translation_data()) {
            return new PWP_Update_Translated_Term_Command($this->handler, $data->get_slug(), $data);
        }
        return new PWP_Update_Term_Command($this->get_service(), $data->get_slug(), $data);
    }

    final public function new_delete_term_command(string $slug): PWP_Delete_Term_Command
    {
        return new PWP_Delete_Term_Command($this->handler, $slug);
    }

    final public function new_create_or_update_command(PWP_Term_Data $data): PWP_I_Command
    {
        return $this->slug_exists($data->get_slug())
            ? $this->new_update_term_command($data)
            : $this->new_create_term_command($data);
    }
}
