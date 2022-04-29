<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\exceptions\PWP_Not_Implemented_Exception;

class PWP_Category_Command_Factory extends PWP_Abstract_Term_Command_Factory
{
    public function __construct()
    {
        parent::__construct('product_cat', 'tax_product_cat', "product category");
    }

    public function create_term_command(): PWP_Create_Term_Command
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
    }

    public function read_term_command(): PWP_Read_Term_Command
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
    }

    public function update_term_command(): PWP_Update_Term_Command
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
    }

    public function delete_term_command(): PWP_Delete_Term_Command
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
    }
}
