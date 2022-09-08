<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\utilities\notification\PWP_Error_Notice;
use PWP\includes\utilities\notification\PWP_Success_Notice;
use PWP\includes\utilities\response\PWP_I_Response_Component;
use PWP\includes\utilities\response\PWP_Response;
use WC_Product_Variation;

final class PWP_Create_Product_Variation_Command implements PWP_I_Command
{
    public function __construct()
    {
    }
    public function do_action(): PWP_I_Response_Component
    {
        $variation = new WC_Product_Variation();
        // $variation->save();
        return new PWP_Error_Notice(
            "method not implemented",
            "method " . __METHOD__ . " not implemented. Undo actions on database entries are not doable."
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
