<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use Error;
use Exception;
use pwp\includes\editor\PWP_Editor_Project;
use PWP\includes\editor\PWP_New_PIE_Project_Request;
use PWP\includes\editor\PWP_PIE_Data;
use PWP\includes\editor\PWP_PIE_Editor_Project;
use PWP\includes\hookables\abstracts\PWP_Abstract_Ajax_Hookable;

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
            if (!isset($_REQUEST['product'])) {
                wp_send_json_error(array('message' => 'missing necessary data!'), 400);
            }
            error_log("incoming add to cart request: " . print_r($_REQUEST, true));

            //find and store all relevant variables
            //first, read and interpret data from request
            $productId      = apply_filters('woocommerce_add_to_cart_product_id', absint($_REQUEST['product']));
            $variationId    = absint($_REQUEST['variant']) ?: 0;
            $editorData     = new PWP_PIE_Data($variationId ?: $productId);
            $quantity       = wc_stock_amount($_REQUEST['quantity'] ?: 1);

            if (apply_filters('woocommerce_add_to_cart_validation', true, $productId, $quantity, $variationId)) {
                if ($editorData->is_customizable() && $editorData->get_template_id()) {
                    //BEGIN CUSTOM PROJECT REDIRECT FLOW
                    session_start();

                    //create custom id for a session variable to store the order data
                    //TODO: should any given user only be able to edit a single order/product at a time? look into it.
                    $sessionId = uniqid('ord');

                    //generate return url which, when called, will add the cached order to the cart.
                    $returnUrl = wc_get_cart_url() . "?CustProj={$sessionId}";
                    $projectData = $this->generate_new_project($editorData, $returnUrl);
                    $itemMeta = $projectData->to_array();

                    //store relevant data in session
                    $_SESSION[$sessionId] = array(
                        'product_id'    => $productId,
                        'quantity'      => $quantity,
                        'variation_id'  => $variationId,
                        'variation'     => [],
                        'item_meta'     => $itemMeta,
                    );

                    wp_send_json_success(
                        array(
                            'message' => 'external project created, redirecting user to editor for customization...',
                            'destination_url' => $projectData->get_project_editor_url(),
                        ),
                        201
                    );
                }

                wp_send_json_success(array(
                    'message' => 'standard product, using default functionality',
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

        //get url of current page:
        // $redirectUrl = get_permalink();
        //get url of cart:
        // $redirectUrl = wc_get_cart_url();
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
    public function generate_new_project(PWP_PIE_Data $data, string $returnURL = ''): PWP_Editor_Project
    {
        if (preg_match('/^(tpl)([0-9A-Z]{3,})$/m', $data->get_template_id())) {
            return $this->new_PIE_Project($data, $returnURL ?: site_url());
        }

        return null;
    }
    /**
     * generate a new project for the Peleman Image Editor
     *
     * @param integer $variant_id product or variant id of the product
     * @param string $returnUrl when the user has completed their project, they will be redirected to this URL
     * @return PWP_PIE_Editor_Project project object
     */
    private function new_PIE_Project(PWP_PIE_DATA $data, string $returnUrl): PWP_PIE_Editor_Project
    {
        //TODO: clean up hardcoded variables and get from options instead.

        return
            PWP_New_PIE_Project_Request::new(
                'https://deveditor.peleman.com/',
                'webshop',
                'X88CPxzXAzunHw2LQ5k6Zat6fCZXCEQqy7Rr6kBnbwj6zM_DOZ6Q-shtgWMM4kI7Iq-r5L2XF7EdjLHHoO4351',
            )->initialize_from_pie_data($data)
            ->set_timeout(10)
            ->set_return_url($returnUrl)
            ->set_user_id(get_current_user_id())
            ->set_language(defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'en')
            ->set_project_name("k" . uniqid())
            ->set_editor_instructions(
                PIE_USE_DESIGN_MODE,
                PIE_USE_BACKGROUNDS,
                PIE_USE_DESIGNS,
                PIE_SHOW_CROP_ZONE,
                PIE_SHOW_SAFE_ZONE,
                PIE_USE_TEXT,
                PIE_USE_ELEMENTS,
                PIE_USE_DESIGNS,
                PIE_USE_OPEN_FILE
            )->make_request();
    }
}
