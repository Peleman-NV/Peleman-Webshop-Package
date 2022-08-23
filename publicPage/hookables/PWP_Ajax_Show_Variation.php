<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\PWP_Product_Meta_Data;
use PWP\includes\hookables\abstracts\PWP_Abstract_Ajax_Hookable;
use WC_Product;

class PWP_Ajax_Show_Variation extends PWP_Abstract_Ajax_Hookable
{
    public function __construct()
    {
        parent::__construct(
            'PWP_Ajax_Show_Variation',
            plugins_url('Peleman-Webshop-Package/publicPage/js/show-variation.js'),
            5

        );
    }

    public function callback(): void
    {
        $variantId = $_REQUEST['variant'];
        $variant = wc_get_product($variantId);
        $editorData = new PWP_Product_Meta_Data($variant);
        $parent = wc_get_product($variant->get_parent_id());

        $response = array(

            'variant'               => $variantId,
            'in_stock'              => $variant->is_in_stock(),
            'is_customizable'       => $editorData->is_customizable(),
            'requires_pdf_upload'   => $editorData->uses_pdf_content(),
            'button_text'           => $this->get_add_to_cart_label($editorData, $parent),
            'pdf_data'              => array(
                'width'                 => $editorData->get_pdf_width(),
                'height'                => $editorData->get_pdf_height(),
                'min_pages'             => $editorData->get_pdf_min_pages(),
                'max_pages'             => $editorData->get_pdf_max_pages(),
                'price_per_page'        => $editorData->get_price_per_page(),
            ),
        );

        wp_send_json_success($response, 200);
    }

    public function callback_nopriv(): void
    {
        $this->callback();
    }

    private function get_add_to_cart_label(PWP_Product_Meta_Data $editorData, WC_Product $parent): string
    {
        if ($editorData->get_custom_add_to_cart_label())
            return $editorData->get_custom_add_to_cart_label();
        if ($parent->get_meta('custom_add_to_cart_label'))
            return $parent->get_meta('custom_add_to_cart_label');
        if (get_option('pwp-custom-add-to-cart-label'))
            return get_option('pwp-custom-add-to-cart-label');
        return '';
    }
}
