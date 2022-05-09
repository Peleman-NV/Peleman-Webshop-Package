<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\wrappers\PWP_Term_Data;

final class PWP_Category_Command_Factory extends PWP_Abstract_Term_Command_Factory
{
    public function __construct(string $defaultLang = 'en')
    {
        $service = new PWP_Term_SVC('product_cat', 'tax_product_cat', "product category", $defaultLang);
        parent::__construct($service);
    }

    final public function new_create_term_command(PWP_Term_Data $data): PWP_Create_Term_Command
    {
        return new PWP_Create_Term_Command($this->service, $data);
    }

    final public function new_read_term_command(array $args = []): PWP_Read_Term_Command
    {
        return new PWP_Read_Term_Command($this->service, $args);
    }

    final public function new_update_term_command(PWP_Term_Data $data, bool $canChangeParent = false): PWP_Update_Term_Command
    {
        return new PWP_Update_Term_Command($this->service, $data, $canChangeParent);
    }

    final public function new_delete_term_command(string $slug): PWP_Delete_Term_Command
    {
        return new PWP_Delete_Term_Command($this->service, $slug);
    }

    final public function new_create_or_update_command(PWP_Term_Data $data, bool $canChangeParent): PWP_I_Command
    {
        return $this->slug_exists($data->get_slug())
            ? $this->new_update_term_command($data, $canChangeParent)
            : $this->new_create_term_command($data);
    }

    final public function get_service(): PWP_Term_SVC
    {
        return $this->service;
    }
}
