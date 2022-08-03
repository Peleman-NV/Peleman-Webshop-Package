<?php

declare(strict_types=1);

namespace PWP\includes;

use PWP\includes\hookables\PWP_Abstract_Action_Component;

class PWP_Add_Custom_Data_To_Cart_Item extends PWP_Abstract_Action_Component
{
    public function __construct(string $hook = 'woocommerce_add_cart_item_data')
    {
        parent::__construct($hook, 'add_custom_data_to_cart_item', 1, 2);
        WC()->cart->add_to_cart()
    }
}