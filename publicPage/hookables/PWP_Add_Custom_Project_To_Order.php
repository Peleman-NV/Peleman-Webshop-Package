<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use pwp\includes\editor\PWP_Editor_Project;
use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;
use WC_Order_Item_Product;

class PWP_Add_Custom_Project_To_Order extends PWP_Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_checkout_create_order_line_item', 'add_custom_data_to_order_line_item', 10, 4);
    }

    public function add_custom_data_to_order_line_item(\WC_Order_Item_Product $item, string $cartItemKey, array $values, \WC_Order $order): void
    {
        //TODO: implements functionality from PPI
        if (isset($values['_editor_id']) && isset($values['_project_id'])) {

            $item->add_meta_data(
                '_editor_id',
                $values['_editor_id'],
                true
            );
            $item->add_meta_data(
                '_project_id',
                $values['_project_id'],
                true
            );
            $item->add_meta_data(
                '_project_url',
                $values['_project_url'],
                true
            );
        }
    }
}
