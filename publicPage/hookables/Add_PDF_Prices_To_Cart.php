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
    public function __construct()
    {
        parent::__construct('woocommerce_before_calculate_totals', 'add_pdf_costs_to_item', 9, 1);
    }

    public function add_pdf_costs_to_item(\WC_Cart $cart): void
    {
        foreach ($cart->get_cart() as $key => $cartItem) {
            $product = $cartItem['data'];
            if (isset($cartItem['_pdf_data']))
                continue;

            $pdfData = $cartItem['_pdf_data'];
            $meta = new Product_Meta_Data($product);


            //update product price with pdf price if applicable
            if ($product instanceof WC_Product && $pdfData) {
                $basePrice = $product->get_price();
                $product->set_price($basePrice + $pdfData['pages'] * $meta->get_price_per_page());
            }

            //override price with cart unit price
            if ($meta->get_unit_amount() > 1) {
                $unit_amount = $meta->get_unit_amount();
                $unit_count = $meta->get_unit_price();
                $cartItem['data']->set_price($meta->get_unit_price());
            }
        }
    }
}
