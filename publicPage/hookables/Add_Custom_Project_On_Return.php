<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;
use WC_Cart;
use WC_Product_Variation;

class Add_Custom_Project_On_Return extends Abstract_Action_Hookable
{
    //this is a rather obscure hook, which is called even after wp_loaded.
    //for some reason, wp_loaded is still too early for this method to be called, but wp is the right timing.
    //TODO: perhaps in the future it might be a better idea to make this an API call, that redirects to the cart.
    public function __construct(string $hook = 'wp')
    {
        parent::__construct($hook, 'add_customized_product_to_cart');
    }

    public function add_customized_product_to_cart()
    {
        if (isset($_REQUEST['CustProj'])) {
            session_start();
            $sessionId = $_REQUEST['CustProj'];

            if (isset($_SESSION[$sessionId])) {

                $data = $_SESSION[$sessionId];
                unset($_SESSION[$sessionId]);

                // error_log("adding project to cart: " . print_r($data, true));

                $productId      = (int)$data['product_id'];
                $variationId    = (int)$data['variation_id'] ?: null;
                $quantity       = $data['quantity'] ?: 1;
                $product        = wc_get_product($variationId ?: $productId);
                $variationArr   = [];
                $meta           = $data['item_meta'];

                if ($product instanceof WC_Product_Variation) {
                    $variationArr = wc_get_product_variation_attributes($variationId);
                }

                if (!WC()->cart->add_to_cart(
                    $productId,
                    $quantity,
                    $variationId,
                    $variationArr,
                    $meta
                )) {
                    wp_die("something went catastrophically wrong adding the item and project to the cart.");
                }
                wc_add_to_cart_message(array($productId => $quantity), true);
            }

            wp_redirect(wc_get_cart_url());
            exit;
        }
    }
}