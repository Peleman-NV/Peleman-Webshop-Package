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
        foreach ($cart->get_cart() as $key => $value) {
            $product = $value['data'];
            if (!isset($value['_pdf_data']))
                continue;

            $pdfData = $value['_pdf_data'];
            if ($product instanceof WC_Product && $pdfData) {
                $meta = new Product_Meta_Data($product);
                $basePrice = $product->get_price();
                $product->set_price($basePrice + $pdfData['pages'] * $meta->get_price_per_page());
            }
        }
    }
}
