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
        $meta = new PWP_Product_Meta_Data($variant);
        $parent = wc_get_product($variant->get_parent_id());

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
                'price_per_page'        => $meta->get_price_per_page() ? $meta->get_price_per_page() : '',
            ),
        );

        wp_send_json_success($response, 200);
    }

    public function callback_nopriv(): void
    {
        $this->callback();
    }

    private function get_add_to_cart_label(PWP_Product_Meta_Data $meta, WC_Product $parent): string
    {
        if ($meta->get_custom_add_to_cart_label())
            return $meta->get_custom_add_to_cart_label();
        if ($parent->get_meta('custom_add_to_cart_label'))
            return $parent->get_meta('custom_add_to_cart_label');
        if (get_option('pwp-custom-add-to-cart-label'))
            return get_option('pwp-custom-add-to-cart-label');
        return 'Add To Cart';
    }
}
