<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\utilities\notification\PWP_I_Notice;
use PWP\includes\utilities\response\PWP_Response;

class PWP_Create_Variation_Product_Command extends PWP_Create_Product_Command
{
    public function __construct(\WC_Product_Variable $parent, array $data)
    {
        $this->parent = $parent;
        parent::__construct($data);
    }

    public function do_action(): PWP_I_Notice
    {
        return PWP_Response::failure('not implemented', 'method not yet implemented.', 501);
    }
}
