<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\editor\Product_Meta_Data;
use PWP\includes\hookables\abstracts\Abstract_Filter_Hookable;

class Change_Add_To_Cart_Archive_Button extends Abstract_Filter_Hookable
{
    public function __construct()
    {
        parent::__construct('woocommerce_loop_add_to_cart_link', 'redirect_add_to_cart', 16, 2);
    }

    public function redirect_add_to_cart(string $button, \WC_Product $product): string
    {
        $meta = new Product_Meta_Data($product);

        if ($product->get_type() === 'variable' || $meta->uses_pdf_content() || $meta->is_editable()) {
            $label = $this->get_label($product, $meta);
            $label = __($label, PWP_TEXT_DOMAIN);
            $redir = $product->get_permalink();
            $button = "<a class='button' href='{$redir}'>{$label}</a>";
        }

        return $button;
    }

    private function get_label(\WC_Product $product, Product_Meta_Data $meta): string
    {
        switch ($product->get_type()) {
            case 'simple':
                return  get_option('pwp_customize_label', 'add to cart');
            case 'variant':
            case 'variable':
                return get_option('pwp_archive_var_label', 'read more');
            case 'external':
            case 'grouped':
            case 'default':
                return 'add to cart';
        }
    }
}
