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
                'name' => '',
                'value' => wc_clean($cart_item['pie_project_id']),
                'display' => '<a href="' . wc_clean($cart_item['pie_project_url']) . '" class="button">edit/review project</a>',
            );
        }
        return $item_data;
    }
}
