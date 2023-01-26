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
            $quantity = $cartItem['quantity'];

            $meta = new Product_Meta_Data($product);
            if (isset($cartItem['_pdf_data'])) {

                $pdfData = $cartItem['_pdf_data'];
                
                //update product price with pdf price if applicable
                if ($product instanceof WC_Product && $pdfData) {
                    $basePrice = $product->get_price();
                    $product->set_price((float)$basePrice + $pdfData['pages'] * $meta->get_price_per_page());
                }
            }

            //override price with cart unit price
            if ($meta->get_unit_amount() > 1 && $meta->get_unit_price() > 0) {
                $cartItem['data']->set_price($meta->get_unit_price());
            }
        }
    }
}
