<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\Product_Meta_Data;
use PWP\includes\hookables\abstracts\Abstract_Filter_Hookable;
use WC_Product;

class Change_Add_To_Cart_Button_Label extends Abstract_Filter_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_product_single_add_to_cart_text', 'change_add_to_cart_button_label', 10, 2);
    }

    public function change_add_to_cart_button_label(string $default, WC_Product $product): string
    {
        $meta = new Product_Meta_Data($product);
        $customizable = $meta->is_customizable();

        // error_log("is product customizable: " . ($customizable ? "yes" : "no"));
        if ($customizable) {
            return $meta->get_custom_add_to_cart_label() ?: "customize & add to cart";
        }

        return $default;
    }
}
