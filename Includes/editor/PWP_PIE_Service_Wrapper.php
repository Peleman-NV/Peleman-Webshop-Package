<?php

declare(strict_types=1);

namespace PWP\includes\editor;

class PWP_PIE_Service_Wrapper implements PWP_IEditor_Service
{
    public function add_to_cart()
    {
        $cart = WC()->cart;
    }
    public function create_new_project()
    {
        
    }
    public function retrieve_project()
    {
    }

    public function add_custom_data_to_order_line()
    {
        
    }
}
