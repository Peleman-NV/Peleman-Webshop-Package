<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\utilities\notification\PWP_Error_Notice;
use PWP\includes\utilities\notification\PWP_Success_Notice;
use PWP\includes\utilities\response\PWP_I_Response_Component;
use WC_Product_Simple;
use WC_Product_Variable;

class PWP_Create_Variable_Product_Command implements PWP_I_Command
{

    public function __construct()
    {
    }

    public function do_action(): PWP_I_Response_Component
    {
        $product = new WC_Product_Simple();
        //todo: define product
        // $product->save();

        return new PWP_Success_Notice(
            'new simple product created',
            "new simple product successfully created!"
        );
    }

    public function undo_action(): PWP_I_Response_Component
    {
        return new PWP_Error_Notice(
            "method not implemented",
            "method " . __METHOD__ . " not implemented. Undo actions on database entries are not doable."
        );
    }
}
