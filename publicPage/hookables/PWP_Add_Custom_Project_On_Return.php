<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;
use WC_Cart;
use WC_Product_Variation;

class PWP_Add_Custom_Project_On_Return extends PWP_Abstract_Action_Hookable
{
    public function __construct(string $hook = 'wp_loaded')
    {
        parent::__construct($hook, 'add_customized_product_to_cart');
    }

    public function add_customized_product_to_cart()
    {
        if (isset($_REQUEST['CustProj'])) {
            session_start();
            error_log("adding project to cart...");
            $sessionId = $_REQUEST['CustProj'];

            if (isset($_SESSION[$sessionId])) {

                $data = $_SESSION[$sessionId];

                error_log(print_r($data, true));

                $productId      = (int)$data['product_id'];
                $variationId    = (int)$data['variation_id'];
                $quantity       = $data['quantity'] ?: 1;
                $product        = wc_get_product($variationId ?: $productId);
                $variationArr   = [];
                $meta           = $data['item_meta'];

                //correction for variatons.
                if ($product instanceof WC_Product_Variation) {
                    $variationArr = wc_get_product_variation_attributes($variationId);
                }

                //we do both of these because it works
                //don't ask me why. it just does. that should suffice.
                do_action('woocommerce_add_to_cart', $productId, $quantity, $variationId, $variationArr, $meta);
                $key =  WC()->cart->add_to_cart(
                    $productId,
                    $quantity,
                    $variationId,
                    $variationArr,
                    $meta
                );
                error_log("key: " . $key);
                wc_add_to_cart_message(array($productId => $quantity), true);
            }
            unset($_SESSION[$sessionId]);

            wp_redirect(wc_get_cart_url());
            exit;
        }
    }
}
