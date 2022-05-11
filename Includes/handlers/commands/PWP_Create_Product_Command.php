<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\utilities\notification\PWP_Error_Notice;
use PWP\includes\utilities\response\PWP_I_Response_Component;

class PWP_Create_Product_Command implements PWP_I_Command
{
    private array $productData;
    private array $productMetaData;

    public function __construct(array $productData, array $productMetaData)
    {
        $this->productData = $productData;
        $this->productMetaData = $productMetaData;
    }

    public function do_action(): PWP_I_Response_Component
    {
        //TODO: implement Product creation through script.
        return new PWP_Error_Notice(
            "method not implemented",
            "method " . __METHOD__ . " not implemented"
        );
    }

    public function undo_action(): PWP_I_Response_Component
    {
        return new PWP_Error_Notice(
            "method not implemented",
            "method " . __METHOD__ . " not implemented"
        );
    }
}
