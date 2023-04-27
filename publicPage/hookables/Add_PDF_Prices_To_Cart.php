<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\Product_Meta_Data;
use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;
use WC_Product;

/** 
 * Calculates the total price of a PDF product based on pages, and sets the price in the cart.
 */
class Add_PDF_Prices_To_Cart extends Abstract_Action_Hookable
{
    public function __construct(int $priority = 10)
    {
        parent::__construct('pwp_modify_cart_item_before_calculate_totals', 'add_pdf_costs_to_item', $priority, 1);
    }

    public function add_pdf_costs_to_item(array $cartItem): void
    {
        /** @var \WC_Product */
        $product = $cartItem['data'];
        $quantity = $cartItem['quantity'];

        $meta = new Product_Meta_Data($product);
        if (!isset($cartItem['_pdf_data'])) {
            return;
        }

        $pdfData = $cartItem['_pdf_data'];

        //update product price with pdf price if applicable
        if ($product instanceof WC_Product && $pdfData) {
            $basePrice = $product->get_price();
            $unitAmount = (int)$product->get_meta('cart_units') ?: 1;
            $product->set_price((float)$basePrice + ($pdfData['pages'] * $meta->get_price_per_page() * $unitAmount));
        }
    }
}
