<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use Error;
use Exception;
use PWP\includes\editor\PWP_Product_Meta_Data;
use pwp\includes\editor\PWP_Editor_Project;
use PWP\includes\editor\PWP_Product_IMAXEL_Data;
use PWP\includes\editor\PWP_IMAXEL_Project;
use PWP\includes\editor\PWP_New_IMAXEL_Project_Request;
use PWP\includes\editor\PWP_New_PIE_Project_Request;
use PWP\includes\editor\PWP_Product_PIE_Data;
use PWP\includes\editor\PWP_PIE_Project;
use PWP\includes\hookables\abstracts\PWP_Abstract_Ajax_Hookable;
use WC_Product_Variation;

class PWP_Ajax_Add_To_Cart extends PWP_Abstract_Ajax_Hookable
{
    public function __construct()
    {
        parent::__construct(
            'PWP_Ajax_Add_To_Cart',
            plugins_url('Peleman-Webshop-Package/publicPage/js/add-to-cart.js'),
            5
        );
    }

    public function callback(): void
    {
        if (!$this->verify_nonce($_REQUEST['nonce'])) {
            error_log("add to cart request received with incorrect nonce value: aborting");
            wp_send_json_error('incorrect nonce', 401);
        }

        try {
            if (!isset($_REQUEST['product'])) {
                //we can safely assume that the product is a simple product that does not require special handling
                wp_send_json_success(array(
                    'message' => "continue as you were",
                    'destination_url' => '',
                ), 200);
            }
            error_log("incoming add to cart request: " . print_r($_REQUEST, true));

            //find and store all relevant variables
            //first, read and interpret data from request
            $productId      = apply_filters('woocommerce_add_to_cart_product_id', absint($_REQUEST['product']));
            $variationId    = apply_filters('woocommerce_add_to_cart_product_id', absint($_REQUEST['variant']));
            $product = wc_get_product($variationId ?: $productId);
            $editorData     = new PWP_Product_Meta_Data($product);
            $quantity       = wc_stock_amount($_REQUEST['quantity'] ?: 1);

            if (apply_filters('woocommerce_add_to_cart_validation', true, $productId, $quantity, $variationId)) {

                if ($editorData->is_customizable()) {
                    //BEGIN CUSTOM PROJECT REDIRECT FLOW
                    error_log("product is customizable. generating project files...");
                    session_start();

                    //create custom id for a session variable to store the order data
                    //TODO: should any given user only be able to edit a single order/product at a time? look into it.
                    $sessionId = uniqid('ord');

                    //generate return url which, when called, will add the cached order to the cart.
                    $returnUrl = wc_get_cart_url() . "?CustProj={$sessionId}";
                    $projectData = $this->generate_new_project($editorData, $returnUrl);

                    //store relevant data in session
                    $_SESSION[$sessionId] = array(
                        'product_id'    => $productId,
                        'quantity'      => $quantity,
                        'variation_id'  => $variationId,
                        'item_meta'     => array(
                            '_editor_id'    => $projectData->get_editor_id(),
                            '_project_id'   => $projectData->get_project_id(),
                            '_project_url'  => $projectData->get_project_editor_url(true),
                        )
                    );

                    wp_send_json_success(
                        array(
                            'message' => 'external project created, redirecting user to editor for customization...',
                            'destination_url' => $projectData->get_project_editor_url(false),
                        ),
                        201
                    );
                    return;
                }
                error_log("product is not customizable. returning to regular flow...");

                $destination = '';
                // if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
                //     wc_add_to_cart_message(array($variationId ?? $productId => $quantity), true);
                //     $destination = wc_get_cart_url();
                // }
                wp_send_json_success(array(
                    'message' => 'standard product, using default functionality',
                    'destination_url' => '',
                ), 200);
            }
            throw new Exception("something unexpected went wrong", 500);
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
    }

    public function callback_nopriv(): void
    {
        $this->callback();
    }

    /**
     * attempt creation of a new project. Method will try to use the template Id to determine what editor is to be used.
     *
     * @param integer $productId id of the product we are trying to edit
     * @param string $templateId template Id of the product. Needed to deterime the appropriate Editor
     * @param string $returnURL url to which the editor will return the user after saving their project, if blank, refer to editor.
     * @return PWP_Editor_Project|null wil return a PWP_Editor_Project object if successful. if the method can not determine a valid editor, will return null.
     */
    public function generate_new_project(PWP_Product_Meta_Data $data, string $returnURL = ''): PWP_Editor_Project
    {
        error_log($data->get_editor_id());
        switch ($data->get_editor_id()) {
            case PWP_Product_PIE_Data::MY_EDITOR:
                return $this->new_PIE_Project($data->pie_data(), $returnURL ?: site_url());
            case PWP_Product_IMAXEL_Data::MY_EDITOR:
                return $this->new_IMAXEL_Project($data->imaxel_data(), $returnURL ?: site_url());
            default:
                return null;
        }
    }
    /**
     * generate a new project for the Peleman Image Editor
     *
     * @param integer $variant_id product or variant id of the product
     * @param string $returnUrl when the user has completed their project, they will be redirected to this URL
     * @return PWP_PIE_Project project object
     */
    private function new_PIE_Project(PWP_Product_PIE_Data $data, string $returnUrl): PWP_PIE_Project
    {
        return
            PWP_New_PIE_Project_Request::new(
                get_option('pie_domain', 'https://deveditor.peleman.com'),
                get_option('pie_customer_id', ''),
                get_option('pie_api_key', ''),
            )->initialize_from_pie_data($data)
            ->set_timeout(10)
            ->set_return_url($returnUrl)
            ->set_user_id(get_current_user_id())
            ->set_language(defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'en')
            ->set_project_name(explode('-', $data->get_parent()->get_name())[0])
            ->set_format_id($data->get_format_id())
            ->set_editor_instructions(
                // PIE_USE_DESIGN_MODE,
                PIE_USE_BACKGROUNDS,
                PIE_USE_DESIGNS,
                PIE_SHOW_CROP_ZONE,
                PIE_SHOW_SAFE_ZONE,
                PIE_USE_TEXT,
                PIE_USE_ELEMENTS,
                PIE_USE_DESIGNS,
                // PIE_USE_OPEN_FILE,
                PIE_USE_IMAGE_UPLOAD,
            )->make_request();
    }

    private function new_IMAXEL_Project(PWP_Product_IMAXEL_Data $data, string $returnUrl): PWP_IMAXEL_Project
    {
        return
            PWP_New_IMAXEL_Project_Request::new()
            ->initialize_from_imaxel_data($data)
            ->set_back_url(wc_get_cart_url())
            ->set_add_to_cart_url($returnUrl)
            ->make_request();
    }
}
