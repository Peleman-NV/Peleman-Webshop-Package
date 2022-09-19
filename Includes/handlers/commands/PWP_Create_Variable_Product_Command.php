<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\editor\PWP_Product_Meta_Data;
use PWP\includes\utilities\notification\PWP_Success_Notice;
use PWP\includes\utilities\response\PWP_I_Response;
use WC_Product_Variable;

class PWP_Create_Variable_Product_Command extends PWP_Create_Product_Command
{
    public function do_action(): PWP_I_Response
    {
        $product = new WC_Product_Variable();

        $product->set_name = $this->data['name'];
        $product->set_reviews_allowed = $this->data['reviews_allowed'] ?: false;

        return new PWP_Success_Notice(
            'new Variable product created',
            "new Variable product successfully created!"
        );
    }
}
