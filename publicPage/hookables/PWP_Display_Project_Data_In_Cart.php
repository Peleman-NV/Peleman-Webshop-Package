<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use pwp\includes\editor\PWP_Editor_Project;
use PWP\includes\hookables\abstracts\PWP_Abstract_Filter_Hookable;

class PWP_Display_Project_Data_In_Cart extends PWP_Abstract_Filter_Hookable
{
    public function __construct()
    {
        parent::__construct(
            'woocommerce_get_item_data',
            'add_project_edit_button_to_cart',
            10,
            2
        );
    }

    public function add_project_edit_button_to_cart(array $item_data, array $cart_item): array
    {
        if (!empty($cart_item['_project_id'])) {
            $item_data[] = array(
                // 'name' => 'edit',
                'value' => wc_clean($cart_item['_project_id']),
                //FIXME: look into a way to regenerate the IMAXEL project url for this url, because each link is only useful -once-
                'display' => '<a href="' . wc_clean($cart_item['_project_url']) . '" class="button">edit/review project</a>',
            );
        }

        return $item_data;
    }
}
