<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\Product_Meta_Data;
use PWP\includes\editor\Editor_Project;
use PWP\includes\editor\New_PIE_Project_Request;
use PWP\includes\editor\PIE_Editor_Instructions;
use PWP\includes\editor\Product_PIE_Data;
use PWP\includes\editor\PIE_Project;
use PWP\includes\exceptions\Invalid_Response_Exception;
use PWP\includes\hookables\abstracts\Abstract_Ajax_Hookable;

/**
 * AJAX method which handles add to cart requests. If the product being added to the cart requires customization,
 * this hook will also generate a new project entry, store the product temporarily in a session, and redirect the user to the
 * appropriate editor.
 */
class Ajax_Add_To_Cart extends Abstract_Ajax_Hookable
{
    public function __construct(int $priority = 10)
    {
        parent::__construct(
            'Ajax_Add_To_Cart',
            plugins_url('../js/pwp-add-to-cart.js', __FILE__),
            $priority
        );
    }

    public function callback(): void
    {
        if (!$this->verify_nonce($_REQUEST['nonce']))
            wp_send_json_error(
                array('message' => __('session timed out', 'Peleman-Webshop-Package')),
                401
            );

        if (!isset($_REQUEST['product_id'])) {
            return;
        }

        try {
            //find and store all relevant variables
            //first, read and interpret data from request
            $productId      = apply_filters('woocommerce_add_to_cart_product_id', sanitize_key($_REQUEST['product_id']));
            $variationId    = apply_filters('woocommerce_add_to_cart_product_id', sanitize_key($_REQUEST['variation_id'])) ?: 0;
            $product        = wc_get_product($variationId ?: $productId);
            $productMeta    = new Product_Meta_Data($product);
            $quantity       = wc_stock_amount((float)$_REQUEST['quantity'] ?: 1);
            $redirectUrl    = '';

            if (apply_filters('woocommerce_add_to_cart_validation', true, $productId, $quantity, $variationId)) {
                wc_clear_notices();
                //store relevant data in session
                if ($productMeta->is_customizable()) {
                    $this->setup_custom_project($productMeta, (int)$productId, (int)$variationId, $quantity);
                }

                $meta = apply_filters(
                    'pwp_add_cart_item_data',
                    array(),
                    $product,
                    $productMeta,
                );

                $itemKey = WC()->cart->add_to_cart($productId, $quantity, $variationId, array(), $meta);
                if ($itemKey) {
                    do_action('woocommerce_ajax_added_to_cart', $productId);

                    if (boolval(get_option('woocommerce_cart_redirect_after_add'))) {
                        wc_add_to_cart_message(array($productId => $quantity), true);
                        $redirectUrl = wc_get_cart_url();
                    }
                    // wc_ajax::get_refreshed_fragments();

                    wp_send_json_success(array(
                        'message' => __('standard product, using default functionality', 'Peleman-Webshop-Package'),
                        'destination_url' => $redirectUrl ?: '',
                    ), 200);
                }
            }
            $notices = wc_get_notices('error');
            $message = $notices[count($notices) - 1]['notice'];

            wc_clear_notices();
            wp_send_json_error(
                array(
                    'message'   => $message,
                    'data'      => 'data validation error has occurred',
                ),
                200
            );
        } catch (Invalid_Response_Exception $err) {
            error_log($err->getMessage());
            wp_send_json_error(
                array('message' => __('Could not connect to Peleman Image Editor. Please try again in a few moments.', 'Peleman-Webshop-Package')),
            );
        } catch (\Exception $err) {
            error_log(sprintf("PHP Error: %s in %s on line %s", $err->getMessage(), $err->getFile(), $err->getLine()));
            error_log($err->getTraceAsString());

            wp_send_json_error(
                array(
                    'message' => __('The System has encountered an unexpected error. Please try again in a few moments.', 'Peleman-Webshop-Package'),
                ),
                200
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

     * @param Product_Meta_Data $data template Id of the product. Needed to deterime the appropriate Editor
     * @param string $returnURL url to which the editor will return the user after saving their project, if blank, refer to editor.
     * @param string $cancelURL url to which the editor will return the user if the user cancels their project.
     * @return Editor_Project|null wil return a Editor_Project object if successful. if the method can not determine a valid editor, will return null.
     */
    public function generate_new_project(Product_Meta_Data $data, string $returnURL = '', string $cancelURL = '', array $params = []): ?Editor_Project
    {
        // error_log($data->get_editor_id());
        switch ($data->get_editor_id()) {
            case Product_PIE_Data::MY_EDITOR:
                return $this->new_PIE_Project($data->pie_data(), $returnURL ?: site_url(), $params);
            default:
                return null;
        }
    }

    /**
     * generate a new project for the Peleman Image Editor
     *
     * @param Product_PIE_Data $data product or variant id of the product
     * @param string $continueUrl when the user has completed their project, they will be redirected to this URL
     * @return PIE_Project project object
     */
    private function new_PIE_Project(Product_PIE_Data $data, string $continueUrl, array $params): PIE_Project
    {
        $instructions = new PIE_Editor_Instructions($data->get_parent());
        $request = new New_PIE_Project_Request(
            get_option('pie_domain', 'https://deveditor.peleman.com'),
            get_option('pie_customer_id', ''),
            get_option('pie_api_key', ''),
        );
        $request->initialize_from_pie_data($data);
        $request->set_return_url($continueUrl);
        $request->set_user_id(get_current_user_id());
        $request->set_language($this->get_site_language() ?: 'en');
        $request->set_project_name($data->get_parent()->get_name());
        $request->set_timeout(10);
        foreach ($params as $param => $value) {
            $request->add_request_parameter($param, $value);
        }
        return $request->make_request();
    }

    private function get_site_language(): string
    {
        if (defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE) {
            return ICL_LANGUAGE_CODE;
        }
        return explode("_", get_locale())[0];
    }

    private function log_upload()
    {
        error_log(__CLASS__ . "\r\nincoming request : " . print_r($_REQUEST, true));
        if (!empty($_FILES)) {
            error_log(__CLASS__ . "\r\nuploaded files : " . print_r($_FILES, true));
        }
    }

    private function setup_custom_project(Product_Meta_Data $productMeta, int $productId, int $variationId, int $quantity)
    {
        $meta = apply_filters(
            'pwp_add_cart_item_data',
            [],
            $productMeta->get_parent(),
            $productMeta,
        );
        $params = apply_filters(
            'pwp_prepare_new_pie_project_params',
            [],
            $productMeta->get_parent(),
            $quantity,
            $meta
        );

        $transientId = uniqid('pwpproj-');
        $continueUrl = wc_get_cart_url() . "?CustProj={$transientId}";
        $cancelUrl = get_permalink($variationId ?: $productId);

        $projectData = $this->generate_new_project($productMeta, $continueUrl, $cancelUrl, $params);

        $meta['_editor_id'] = $projectData->get_editor_id();
        $meta['_project_id'] = $projectData->get_project_id();
        $itemData = array(
            'product_id'    => $productId,
            'quantity'      => $quantity,
            'variation_id'  => $variationId,
            'item_meta'     => $meta,

        );


        //transient expires in 30 days
        set_transient($transientId, $itemData, 30 * 86400);

        // error_log(print_r($projectData->get_project_editor_url(false), true));
        wp_send_json_success(
            array(
                'message' => __('external project created, redirecting user to editor for customization...', 'Peleman-Webshop-Package'),
                'destination_url' => $projectData->get_project_editor_url(false),
            ),
            201
        );
    }
}
