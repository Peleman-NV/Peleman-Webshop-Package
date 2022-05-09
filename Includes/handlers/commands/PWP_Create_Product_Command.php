<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Response;

class PWP_Create_Product_Command implements PWP_I_Command
{
    private array $productData;
    private array $productMetaData;

    public function __construct(array $productData, array $productMetaData)
    {
        $this->productData = $productData;
        $this->productMetaData = $productMetaData;
    }

    public function do_action(): PWP_I_Response
    {
        //TODO: implement Product creation through script.
        return new PWP_Response("method not implemented", false);
    }

    public function undo_action(): PWP_I_Response
    {
        return new PWP_Response("method not implemented", false);
    }
}
