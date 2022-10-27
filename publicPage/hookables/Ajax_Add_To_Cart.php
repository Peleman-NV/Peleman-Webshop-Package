<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\Product_Meta_Data;
use pwp\includes\editor\Editor_Project;
use PWP\includes\editor\Product_IMAXEL_Data;
use PWP\includes\editor\IMAXEL_Project;
use PWP\includes\editor\New_IMAXEL_Project_Request;
use PWP\includes\editor\New_PIE_Project_Request;
use PWP\includes\editor\PIE_Editor_Instructions;
use PWP\includes\editor\Product_PIE_Data;
use PWP\includes\editor\PIE_Project;
use PWP\includes\hookables\abstracts\Abstract_Ajax_Hookable;
use WC_AJAX;

class Ajax_Add_To_Cart extends Abstract_Ajax_Hookable
{
    public function __construct()
    {
        parent::__construct(
            'Ajax_Add_To_Cart',
            plugins_url('Peleman-Webshop-Package/publicPage/js/pwp-add-to-cart.js'),
            5
        );
    }

    public function callback(): void
    {
        ob_start();
        // $this->log_upload();

        if (!$this->verify_nonce($_REQUEST['nonce']))
            wp_send_json_error(
                array('message' => __('session timed out', PWP_TEXT_DOMAIN)),
                401
            );

        if (!isset($_REQUEST['product_id']))
            return;

        try {
            //find and store all relevant variables
            //first, read and interpret data from request
            $productId      = apply_filters('woocommerce_add_to_cart_product_id', absint($_REQUEST['product_id']));
            $variationId    = apply_filters('woocommerce_add_to_cart_product_id', absint($_REQUEST['variation_id']));
            $product        = wc_get_product($variationId ?: $productId);
            $productMeta     = new Product_Meta_Data($product);
            $quantity       = wc_stock_amount($_REQUEST['quantity'] ?: 1);

            error_log(print_r($_REQUEST, true));
            error_log('product id: ' . $productId . ", variation id : " . $variationId);

            if (apply_filters('woocommerce_add_to_cart_validation', true, $productId, $quantity, $variationId)) {
                wc_clear_notices();
                if ($productMeta->is_customizable()) {

                    // error_log("product is customizable. generating project files...");
                    session_start();

                    $sessionId = uniqid('ord');

                    $continueUrl = wc_get_cart_url() . "?CustProj={$sessionId}";
                    $cancelUrl = get_permalink($product->get_id());

                    $projectData = $this->generate_new_project($productMeta, $continueUrl, $cancelUrl);

                    $itemData = array(
                        'product_id'    => $productId,
                        'quantity'      => $quantity,
                        'variation_id'  => $variationId,
                        'item_meta'     => array(
                            '_editor_id'    => $projectData->get_editor_id(),
                            '_project_id'   => $projectData->get_project_id(),
                        )
                    );

                    //store relevant data in session
                    /**
                     * @var array $itemData array of data to be stored in the session until user returns
                     * @var \WC_Product $product product which is to be stored
                     * @var Product_Meta_Data $productMeta product meta data object
                     */
                    $itemData['item_meta'] = apply_filters(
                        'pwp_add_cart_item_data',
                        $itemData['item_meta'],
                        $product,
                        $productMeta,
                    );

                    $_SESSION[$sessionId] = $itemData;

                    wp_send_json_success(
                        array(
                            'message' => __('external project created, redirecting user to editor for customization...', PWP_TEXT_DOMAIN),
                            'destination_url' => $projectData->get_project_editor_url(false),
                        ),
                        201
                    );
                    return;
                }

                //store relevant data in session
                /**
                 * @var array $itemData array of data to be stored in the session until user returns
                 * @var \WC_Product $product product which is to be stored
                 * @var Product_Meta_Data $productMeta product meta data object
                 */
                $meta = apply_filters(
                    'pwp_add_cart_item_data',
                    array(),
                    $product,
                    $productMeta,
                );

                if (WC()->cart->add_to_cart($productId, $quantity, $variationId, array(), $meta)) {
                    do_action('woocommerce_ajax_added_to_cart', $productId);

                    if (boolval(get_option('woocommerce_cart_redirect_after_add'))) {
                        wc_add_to_cart_message(array($productId => $quantity), true);
                        $redirectUrl = wc_get_cart_url();
                    }
                    // wc_ajax::get_refreshed_fragments();

                    wp_send_json_success(array(
                        'message' => __('standard product, using default functionality', PWP_TEXT_DOMAIN),
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
        } catch (\Exception $err) {
            error_log(sprintf("PHP Error: %s in %s on line %s", $err->getMessage(), $err->getFile(), $err->getLine()));
            error_log($err->getTraceAsString());

            wp_send_json_error(
                array(
                    'message' => __('an unexpected error has occurred', PWP_TEXT_DOMAIN),
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
     * @param string $cancelURL url to which the editor will return the user if the user cancels their project.
     * @return Editor_Project|null wil return a Editor_Project object if successful. if the method can not determine a valid editor, will return null.
     */
    public function generate_new_project(Product_Meta_Data $data, string $returnURL = '', string $cancelURL = ''): Editor_Project
    {
        // error_log($data->get_editor_id());
        switch ($data->get_editor_id()) {
            case Product_PIE_Data::MY_EDITOR:
                return $this->new_PIE_Project($data->pie_data(), $returnURL ?: site_url());
            case Product_IMAXEL_Data::MY_EDITOR:
                return $this->new_IMAXEL_Project($data->imaxel_data(), $returnURL ?: site_url());
            default:
                return null;
        }
    }
    /**
     * generate a new project for the Peleman Image Editor
     *
     * @param integer $variant_id product or variant id of the product
     * @param string $continueUrl when the user has completed their project, they will be redirected to this URL
     * @return PIE_Project project object
     */
    private function new_PIE_Project(Product_PIE_Data $data, string $continueUrl): PIE_Project
    {
        $instructions = new PIE_Editor_Instructions($data->get_parent());
        return
            New_PIE_Project_Request::new(
                get_option('pie_domain', 'https://deveditor.peleman.com'),
                get_option('pie_customer_id', ''),
                get_option('pie_api_key', ''),
            )->initialize_from_pie_data($data)
            ->set_timeout(10)
            ->set_return_url($continueUrl)
            ->set_user_id(get_current_user_id())
            ->set_language($this->get_site_language())
            ->set_project_name($data->get_parent()->get_name())
            // ->set_editor_instructions(
            //     PIE_USE_BACKGROUNDS,
            //     PIE_USE_TEXT,
            //     PIE_USE_ELEMENTS,
            //     PIE_USE_IMAGE_UPLOAD,
            // )
            ->make_request();
    }

    private function new_IMAXEL_Project(Product_IMAXEL_Data $data, string $continueUrl): IMAXEL_Project
    {
        return
            New_IMAXEL_Project_Request::new()
            ->initialize_from_imaxel_data($data)
            ->set_back_url(wc_get_cart_url())
            ->set_add_to_cart_url($continueUrl)
            ->make_request();
    }

    private function get_site_language(): string
    {
        if (defined('ICL_LANGUAGE_CODE')) {
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
}
