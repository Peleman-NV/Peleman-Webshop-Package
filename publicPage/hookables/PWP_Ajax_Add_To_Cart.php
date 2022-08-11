<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use Error;
use IWPML_Current_Language;
use pwp\includes\editor\PWP_Editor_Project;
use PWP\includes\editor\PWP_PIE_Data;
use PWP\includes\editor\PWP_PIE_Create_Project_Request_Data;
use PWP\includes\Editor\PWP_Pie_Editor_Request;
use PWP\includes\editor\PWP_PIE_Editor_Project;
use PWP\includes\hookables\abstracts\PWP_Abstract_Ajax_Hookable;
use WC_AJAX;
use WC_Product_Variation;

class PWP_Ajax_Add_To_Cart extends PWP_Abstract_Ajax_Hookable
{
    public function __construct()
    {
        parent::__construct(
            'PWP_Ajax_Add_To_Cart',
            plugins_url('Peleman-Webshop-Package/publicPage/js/add-to-cart.js'),
        );
    }

    public function callback(): void
    {
        try {
            if (!isset($_POST['product'])) {
                wp_send_json_error(array('message' => 'missing necessary data!'), 400);
            }
            error_log("incoming add to cart request: " . print_r($_REQUEST, true));

            //find and store all relevant variables
            $productID      = apply_filters('woocommerce_add_to_cart_product_id', absint($_REQUEST['product']));
            $variationID    = absint($_REQUEST['variant']) ?: 0;
            $product        = wc_get_product($variationID ?: $productID);
            $quantity       = wc_stock_amount($_REQUEST['quantity'] ?: 1);
            $customizable   = ((int)$product->get_meta('pie_customizable') === 1);
            $templateID     = $product->get_meta('template_id');
            //TODO: use validation
            $validated      = apply_filters('woocommerce_add_to_cart_validation', true, $productID, $quantity, $variationID);
            $variation      = [];
            $itemMeta       = [];
            $redirectUrl    = '';

            // //adjust data if product is a variation
            // if ($product && $product instanceof WC_Product_Variation) {
            //     // $variationID    = $productID;
            //     $productID      = $product->get_parent_id();
            //     $variation      = $product->get_variation_attributes();
            // }

            error_log("customizable: " . ($customizable ? 'true' : 'false'));

            if ($validated) {
                if ($customizable && $templateID) {
                    session_start();

                    //create custom id for a session variable to store the order data
                    //TODO: should any given user only be able to edit a single order/product at a time? look into it.
                    $sessionID = uniqid('ord');

                    //generate return url which, when called, will add the cached order to the cart.
                    $returnUrl = wc_get_cart_url() . "?CustProj={$sessionID}";

                    //generate new project data
                    $projectData = $this->generate_new_project($variationID, $templateID, $returnUrl);

                    $itemMeta = array(
                        'editor'            => $projectData->get_editor_id(),
                        'pie_project_id'    => $projectData->get_project_id(),
                        'pie_project_url'   => $projectData->get_project_editor_url(),
                    );

                    //store relevant data in session
                    $_SESSION[$sessionID] = array(
                        'product_id'    => $productID,
                        'quantity'      => $quantity,
                        'variation_id'  => $variationID,
                        'variation'     => $variation,
                        'item_meta'     => $itemMeta,
                    );

                    wp_send_json_success(
                        array(
                            'message' => 'external project created, redirecting user to editor for customization...',
                            'destination_url' => $projectData->get_project_editor_url(),
                        ),
                        200
                    );
                }

                wp_send_json_success(array(
                    'message' => 'standard product, using default functionality',
                    'destination_url' => $redirectUrl,
                ), 200);
            }
            wp_send_json_error(array('message' => 'something screwed up'), 420);
        } catch (Error $err) {
            error_log(sprintf("PHP Error: %s in %s on line %s", $err->getMessage(), $err->getFile(), $err->getLine()));
            error_log($err->getTraceAsString());

            wp_send_json_error(
                array(
                    'message' => 'an unexpected error has occurred',
                ),
                500
            );
        }

        //get url of current page:
        // $redirectUrl = get_permalink();
        //get url of cart:
        // $redirectUrl = wc_get_cart_url();
    }

    public function callback_nopriv(): void
    {
        $this->callback();
    }

    public function generate_new_project(int $productID, string $templateID, string $returnURL = ''): PWP_Editor_Project
    {
        if (preg_match('/^(tpl)([0-9A-Z]{3,})$/m', $templateID)) {
            $projectID = $this->new_PIE_Project($productID, $templateID, $returnURL ?: site_url());
        }
        return new PWP_PIE_Editor_Project($projectID);
    }
    /**
     * generate a new project for the Peleman Image Editor
     *
     * @param integer $variant_id product or variant id of the product
     * @param string $template_id template id of the project to be created
     * @param string $returnUrl when the user has completed their project, they will be redirected to this URL
     * @return integer project ID
     */
    private function new_PIE_Project(int $variant_id, string $template_id, string $returnUrl): string
    {
        $request = new PWP_Pie_Editor_Request('https://deveditor.peleman.com');

        //TODO: handle data properly.
        $requestData = new PWP_PIE_Create_Project_Request_Data(
            (string)get_current_user_id(),
            new PWP_PIE_Data($variant_id),
            $returnUrl
        );

        $requestData->set_language(defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'en');
        $requestData->set_project_name("k" . uniqid());
        $requestData->set_editor_instructions(
            USE_DESIGN_MODE,
            USE_BACKGROUNDS,
            USE_DESIGNS,
            SHOW_CROP_ZONE,
            SHOW_SAFE_ZONE,
            USE_TEXT,
            USE_ELEMENTS,
            USE_DESIGNS,
            USE_OPEN_FILE
        );

        //TODO: how to handle project names? let users define them on the editor side? use random UUID?
        //      right now generate a random default name.
        //      need to do the requestData in an authenticated manner: use an API key?
        //      requires a response: response should allow me to redirect the user to their editor page.


        //TODO: handle PDF file uploads
        // $content_file_id = sanitize_text_field($_GET['content']);

        return (string)$request->create_new_project($requestData);

        // return (string)(random_int(0, PHP_INT_MAX));
    }
}
