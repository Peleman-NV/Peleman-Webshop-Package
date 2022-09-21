<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\utilities\response\PWP_Error_Response;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Response;

class PWP_Create_Variation_Product_Command extends PWP_Create_Product_Command
{
    public function __construct(\WC_Product_Variable $parent, array $data)
    {
        $this->parent = $parent;
        parent::__construct($data);
    }

    public function do_action(): PWP_I_Response
    {
        return new PWP_Error_Response('method not yet implemented.', 501);
    }
}
