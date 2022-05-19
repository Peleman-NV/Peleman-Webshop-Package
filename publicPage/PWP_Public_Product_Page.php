<?php

declare(strict_types=1);

namespace PWP\publicPage;

use WC_Product;
use setasign\Fpdi\Tfpdf\Fpdi;
use PWP\includes\wrappers\PWP_File_Data;
use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\includes\hookables\PWP_I_Hookable_Component;
use PWP\includes\traits\PWP_Hookable_Parent_Trait;

class PWP_Public_Product_Page implements PWP_I_Hookable_Component
{
    use PWP_Hookable_Parent_Trait;

    public function register_hooks(PWP_Plugin_Loader $loader): void
    {
        $loader->add_filter('woocommerce_product_add_to_cart_text', $this, 'change_add_to_cart_text_for_archive');
        $loader->add_filter('woocommerce_product_single_add_to_cart_text', $this, 'change_add_to_cart_text_for_product');

        $this->add_child_hookable(new pwp_upload_content());
        $this->add_child_hookable(new pwp_add_to_cart());

        $this->register_child_hooks($loader);
    }

    public function change_add_to_cart_text_for_product(string $defaultText): string
    {
        return "buy me now!!!";
    }

    public function change_add_to_cart_text_for_archive(string $defaultText): string
    {
        global $product;

        if ($product instanceof WC_Product) {
            //switch case to differentiate between product types and change button text for each type
            //TODO: purely experimental, change in final release
            switch ($product->get_type()) {
                case 'variable':
                    return "check out this variable product!";
                case 'grouped':
                    return "have a look at this grouped product!";
                case 'simple':
                case 'external':
                default:
                    return $defaultText;
            }
        }

        return $defaultText;
    }
}
