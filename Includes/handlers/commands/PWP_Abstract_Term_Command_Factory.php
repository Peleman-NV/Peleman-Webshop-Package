<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\wrappers\PWP_Term_Data;

abstract class PWP_Abstract_Term_Command_Factory
{
    protected PWP_Term_SVC $service;

    public function __construct(PWP_Term_SVC $service)
    {
        $this->service = $service;
        $this->service->disable_sitepress_get_term_filter();
    }

    abstract public function new_create_term_command(PWP_Term_Data $data): PWP_Create_Term_Command;

    abstract public function new_read_term_command(array $args = []): PWP_Read_Term_Command;

    abstract public function new_update_term_command(PWP_Term_Data $data, bool $canChangeParent): PWP_Update_Term_Command;

    abstract public function new_delete_term_command(string $slug): PWP_Delete_Term_Command;

    final public function slug_exists(string $slug): bool
    {
        return !is_null($this->service->get_item_by_slug($slug));
    }
}
