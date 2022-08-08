<?php

declare(strict_types=1);

namespace PWP\includes\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Filter_Hookable;

class PWP_Add_Project_Data_To_Cart extends PWP_Abstract_Filter_Hookable
{
    public function __construct()
    {
        parent::__construct(
            'woocommerce_add_cart_item_data',
            'add_PIE_Project_data_to_cart',
            10,
            4
        );
    }

    /**
     * filter method for adding custom project data to a cart item
     *
     * @param array $cart_item_data array of cart item data
     * @param integer $product_id ID of the product added to the cart
     * @param integer $variant_id variant ID of the product added to the cart
     * @param integer $quantity quantity of the item added to the cart
     * @return array
     */
    public function add_PIE_Project_data_to_cart(array $cart_item_data, int $product_id, int $variant_id, int $quantity): array
    {
        $project_id = $_POST['project_id'];
        if (!empty($project_id)) {
            $cart_item_data['pie_project_id'] = uniqid("id");
        }
        return $cart_item_data;
    }
}
