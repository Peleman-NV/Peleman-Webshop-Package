<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;

class PWP_Save_Cart_Item_Meta_To_Order_Item extends PWP_Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_checkout_create_order_line_item', 'save_cart_item_meta_to_order_item', 10, 4);
    }

    public function save_cart_item_meta_to_order_item($item, $cart_item_key, $values, $order): void
    {
        $projectKey = '';
        $editorIdKey = '';
    }
}
