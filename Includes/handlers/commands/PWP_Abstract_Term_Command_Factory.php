<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\wrappers\PWP_Term_Data;

abstract class PWP_Abstract_Term_Command_Factory
{
    private string $taxonomy;
    private string $elementType;
    private string $beautyName;

    protected PWP_Term_SVC $service;

    public function __construct(string $taxonomy, string $elementType, string $beautyName)
    {
        $this->taxonomy = $taxonomy;
        $this->elementType = $elementType;
        $this->beautyName = $beautyName;

        $this->service = new PWP_Term_SVC($taxonomy, $elementType, $beautyName, 'en');
    }

    abstract public function new_create_term_command(PWP_Term_Data $data): PWP_Create_Term_Command;

    abstract public function new_read_term_command(array $args = []): PWP_Read_Term_Command;

    abstract public function new_update_term_command(PWP_Term_Data $data): PWP_Update_Term_Command;

    abstract public function new_delete_term_command(string $slug): PWP_Delete_Term_Command;

    final public function slug_exists(string $slug, string $lang): bool
    {
        return $this->service->get_item_by_slug($slug, $lang) ? true : false;
    }
}
