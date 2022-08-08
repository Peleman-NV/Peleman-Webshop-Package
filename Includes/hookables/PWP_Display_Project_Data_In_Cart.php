<?php

declare(strict_types=1);

namespace PWP\includes\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Filter_Hookable;

class PWP_Display_Project_Data_In_Cart extends PWP_Abstract_Filter_Hookable
{
    public function __construct()
    {
        parent::__construct(
            'woocommerce_get_item_data',
            'pwp_display_project_data',
            10,
            2
        );
    }

    public function pwp_display_project_data(array $item_data, $cart_item): array
    {
        if (!empty($cart_item['pie_project_id'])) {
            $item_data[] = array(
                // 'key' => __('project ID', PWP_TEXT_DOMAIN),
                'value' => wc_clean($cart_item['pie_project_id']),
                //TODO: look into what this does and how we can use it in the future
                //TODO: look into an URL so that the customer can still edit this project from the cart.
                'display' => '<a href="https://deveditor.peleman.com/?projectid=' . wc_clean($cart_item['pie_project_id']) . '" class="button">edit/review project</a>',
            );
        }
        return $item_data;
    }
}
