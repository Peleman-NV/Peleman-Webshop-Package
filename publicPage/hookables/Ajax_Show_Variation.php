<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\Product_Meta_Data;
use PWP\includes\hookables\abstracts\Abstract_Ajax_Hookable;
use WC_Product;

class Ajax_Show_Variation extends Abstract_Ajax_Hookable
{
    public function __construct()
    {
        parent::__construct(
            'Ajax_Show_Variation',
            plugins_url('Peleman-Webshop-Package/publicPage/js/pwp-show-variation.js'),
            5
        );
    }

    public function callback(): void
    {

        $variantId = sanitize_key($_REQUEST['variant']);
        $variant = wc_get_product($variantId);
        $meta = new Product_Meta_Data($variant);
        $parent = wc_get_product($variant->get_parent_id());

        // error_log(print_r($meta, true));

        $response = array(
            'variant'               => $variantId,
            'in_stock'              => $variant->is_in_stock(),
            'is_customizable'       => $meta->is_customizable(),
            'requires_pdf_upload'   => $meta->uses_pdf_content(),
            'button_text'           => $this->get_add_to_cart_label($meta, $parent),
            'pdf_data'              => array(
                'width'                 => $meta->get_pdf_width() ? $meta->get_pdf_width() . ' mm' : '',
                'height'                => $meta->get_pdf_height() ? $meta->get_pdf_height() . ' mm' : '',
                'min_pages'             => $meta->get_pdf_min_pages() ? $meta->get_pdf_min_pages() : '',
                'max_pages'             => $meta->get_pdf_max_pages() ? $meta->get_pdf_max_pages() : '',
                'price_per_page'        => $meta->get_price_per_page() ? wc_price($meta->get_price_per_page()) : '',
            ),
        );

        wp_send_json_success($response, 200);
    }

    public function callback_nopriv(): void
    {
        $this->callback();
    }

    private function get_add_to_cart_label(Product_Meta_Data $meta, WC_Product $parent): string
    {
        if ($meta->get_custom_add_to_cart_label())
            return $meta->get_custom_add_to_cart_label();
        if ($parent->get_meta('custom_add_to_cart_label'))
            return $parent->get_meta('custom_add_to_cart_label');
        if ($meta->is_customizable()) {
            return get_option('pwp_customize_label', __('customize product', PWP_TEXT_DOMAIN));
        }
        return 'Add To Cart';
    }
}
