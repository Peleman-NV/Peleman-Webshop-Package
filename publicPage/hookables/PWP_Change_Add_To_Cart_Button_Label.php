<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\PWP_Editor_Data;
use PWP\includes\hookables\abstracts\PWP_Abstract_Filter_Hookable;
use WC_Product;

class PWP_Change_Add_To_Cart_Button_Label extends PWP_Abstract_Filter_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_product_single_add_to_cart_text', 'change_add_to_cart_button_label', 10, 2);
    }

    public function change_add_to_cart_button_label(string $default, WC_Product $product): string
    {
        error_log($product->get_meta('customizable_product'));
        if (boolval($product->get_meta('customizable_product'))) {
            return $product->get_meta('custom_add_to_cart_label') ?: "customize & add to cart";
        }

        return $default;
    }
}
