<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Filter_Hookable;

class PWP_Change_Add_To_Cart_Label_For_Archive extends PWP_Abstract_Filter_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_product_add_to_cart_text', 'change_add_to_cart_text_for_archive', 10, 2);
    }

    public function change_add_to_cart_text_for_archive(string $default, \WC_Product $product): string
    {
        switch ($product->get_type()) {
            case 'variable':
                return "Customize me";
            case 'grouped':
            case 'simple':
            case 'external':
            default:
                return $default;
        }
    }
}
