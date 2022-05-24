<?php

declare(strict_types=1);

namespace PWP\publicPage;

use WC_Product;
use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\includes\hookables\PWP_I_Hookable_Component;
use PWP\includes\traits\PWP_Hookable_Parent_Trait;

class PWP_Public_Product_Page implements PWP_I_Hookable_Component
{
    use PWP_Hookable_Parent_Trait;

    public function register_hooks(PWP_Plugin_Loader $loader): void
    {
        $loader->add_action('woocommerce_before_add_to_cart_form', $this, 'display_file_output_form', 7, 1);
        // $loader->add_action('woocommerce_locate_template', $this, 'override_wc_templates', 10, 3);
        $loader->add_filter('woocommerce_product_add_to_cart_text', $this, 'change_add_to_cart_text_for_archive');
        $loader->add_filter('woocommerce_product_single_add_to_cart_text', $this, 'change_add_to_cart_text_for_product');


        $this->add_hookable(new pwp_upload_content());
        $this->add_hookable(new pwp_add_to_cart());
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

    public function display_file_output_form(): void
    {
        echo "<p>test0254</p>";
        include PWP_TEMPLATES_DIR . '/' . 'PWP_File_Upload_Form_Template.php';
    }

    public function override_wc_templates(string $template): string
    {
        if ('variation.php' === basename($template)) {
            return trailingslashit(plugin_dir_path(__FILE__)) . '../templates/wc/PWP-Variation.php';
        }
        if ('variation-add-to-cart-button.php' === basename($template)) {
            return trailingslashit(plugin_dir_path(__FILE__)) . '../templates/wc/PWP-Variation-Add-To-Cart-Button.php';
        }
        return $template;
    }
}
