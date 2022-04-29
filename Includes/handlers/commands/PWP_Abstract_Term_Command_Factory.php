<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

abstract class PWP_Abstract_Term_Command_Factory
{
    private string $taxonomy;
    private string $elementType;
    private string $beautyName;

    public function __construct(string $taxonomy, string $elementType, string $beautyName)
    {
        $this->taxonomy = $taxonomy;
        $this->elementType = $elementType;
        $this->beautyName = $beautyName;
    }

    abstract public function create_term_command(): PWP_Create_Term_Command;

    abstract public function read_term_command(): PWP_Read_Term_Command;

    abstract public function update_term_command(): PWP_Update_Term_Command;

    abstract  public function delete_term_command(): PWP_Delete_Term_Command;
}
