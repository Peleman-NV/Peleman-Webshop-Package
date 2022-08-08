<?php

declare(strict_types=1);

namespace PWP\includes\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Filter_Hookable;

class PWP_Add_PDF_Contents_To_Cart extends PWP_Abstract_Filter_Hookable
{
    public function __construct()
    {
        parent::__construct(
            'woocommerce_add_cart_item_data',
            'add_PDF_Contents_to_cart',
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
    public function add_PDF_Contents_to_cart(array $cart_item_data, int $product_id, int $variant_id, int $quantity): array
    {
        $content = $_POST['pdf_content'];
        if (!empty($product_id)) {
            // $cart_item_data['pdf_content'] = content;
        }
        return $cart_item_data;
    }
}
