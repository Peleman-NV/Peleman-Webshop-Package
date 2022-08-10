<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;
use WC_Cart;

class PWP_Add_Custom_Project_On_Return extends PWP_Abstract_Action_Hookable
{
    //why are we using the woocommerce_after_register_post_type hook to call this hookable?
    //because woocommerce is a tale told by an idiot
    //WC_Cart()->add_to_cart() method ONLY works after three specific hooks have been called. these three hooks are NOT called BEFORE the plugins_loaded hook
    //meaning the safest bet to call this hook is to call the last of the three hooks, which is this one
    //it's arbitrary, it's roundabout, it's stupid, but it works and I am a very tired and angry programmer

    public function __construct(string $hook = 'woocommerce_after_register_post_type')
    {
        parent::__construct($hook, 'add_customized_product_to_cart');
    }

    public function add_customized_product_to_cart()
    {
        if (isset($_REQUEST['CustProj'])) {
            session_start();
            error_log("adding project to cart...");
            $sessionID = $_REQUEST['CustProj'];

            error_log($sessionID);
            if (isset($_SESSION[$sessionID])) {

                $data = $_SESSION[$sessionID];

                error_log(print_r($data, true));

                $productID = $data['product_id'];
                $quantity = $data['quantity'];
                $variationID = $data['variation_id'];
                $variation = $data['variation'];
                $meta = $data['item_meta'];

                if (WC()->cart === null) {
                    //why? because woocommerce is silly. We've been over this.
                    //simply put, if the cart is empty, it does not exist
                    //and has to be initialized
                    WC()->initialize_cart();
                }
                WC()->cart->add_to_cart(
                    $productID,
                    $quantity,
                    $variationID,
                    $variation,
                    $meta
                );
            }

            //redirect back to cart to get rid of that pesky GET parameter.
            unset($_SESSION[$sessionID]);
            wp_redirect(wc_get_cart_url());
            exit;
        }
    }
}
