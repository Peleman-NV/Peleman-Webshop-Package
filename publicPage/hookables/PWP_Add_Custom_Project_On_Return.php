<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;
use WC_Cart;
use WC_Product_Variation;

class PWP_Add_Custom_Project_On_Return extends PWP_Abstract_Action_Hookable
{
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
                error_log("adding project to cart...");

                $data = $_SESSION[$sessionId];

                error_log(print_r($data, true));

                $productId      = (int)$data['product_id'];
                $variationId    = (int)$data['variation_id'] ?: null;
                $quantity       = $data['quantity'] ?: 1;
                $product        = wc_get_product($variationId ?: $productId);
                $variationArr   = [];
                $meta           = $data['item_meta'];

                //correction for variatons.
                if ($product instanceof WC_Product_Variation) {
                    // $productId = $variationId;
                    $variationArr = wc_get_product_variation_attributes($variationId);
                }

                $itemKey =  WC()->cart->add_to_cart(
                    $productId,
                    $quantity,
                    $variationId,
                    $variationArr,
                    $meta
                    // [],
                );
                // do_action('woocommerce_add_to_cart', $itemKey, $productId, $quantity, $variationId, $variationArr, $meta);
                error_log("item key: " . $itemKey);
                wc_add_to_cart_message(array($productId => $quantity), true);

                error_log(' ');
                error_log(print_r(WC()->cart->get_cart_item($itemKey),true));
            }
            unset($_SESSION[$sessionId]);

            wp_redirect(wc_get_cart_url());
            exit;
        }
    }
}
