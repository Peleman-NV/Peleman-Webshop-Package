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

            error_log($sessionId);
            if (isset($_SESSION[$sessionId])) {

                $data = $_SESSION[$sessionId];

                error_log(print_r($data, true));

                $productId      = (int) apply_filters('woocommerce_add_to_cart_product_id', $data['product_id']);
                $variationId    = (int) apply_filters('woocommerce_add_to_cart_product_id', $data['variation_id']);
                $quantity       = $data['quantity'];
                $product        = wc_get_product($variationId ?: $productId);
                $variationArr   = [];
                $meta           = $data['item_meta'];

                //correction for variatons.
                if ($product && $product instanceof WC_Product_Variation) {
                    error_log("variation!");
                    $variationArr = wc_get_product_variation_attributes($variationId);
                    error_log(print_r($variationArr, true));
                }

                if (WC()->cart === null)
                    WC()->initialize_cart();
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
            // exit;
        }
    }
}
