<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\Product_Meta_Data;
use PWP\includes\hookables\abstracts\Abstract_Filter_Hookable;
use WC_Product;

/**
 * Change add to cart button label based on whether the product is customizable or has a custom label set
 * in the product page.
 */
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

        if ($customizable) {
            if (!empty($meta->get_custom_add_to_cart_label())) {
                return $meta->get_custom_add_to_cart_label() ?: $default;
            }
            if ($product instanceof \WC_Product_Simple) {
                error_log('is simple');
                return (get_option('pwp_customize_label', '') ?: $default);
            }
            if ($product instanceof \WC_Product_Variation) {
                return (get_option('pwp_archive_var_label', '') ?: $default);
            }
        }

        return $default;
    }
}
