<?php

declare(strict_types=1);

namespace PWP\publicPage;

use PWP\includes\editor\PWP_editor_client;
use PWP\includes\hookables\PWP_I_Hookable_Component;
use PWP\includes\loaders\PWP_Plugin_Loader;
use WC_Product;

class PWP_Public_Product_Page implements PWP_I_Hookable_Component
{
    public function register_hooks(PWP_Plugin_Loader $loader): void
    {
        $loader->add_action('wp_enqueue_scripts', $this, 'enqueue_styles');
        $loader->add_action('wp_enqueue_scripts', $this, 'enqueue_ajax', 8);

        $loader->add_filter('woocommerce_product_add_to_cart_text', $this, 'change_add_to_cart_text_for_archive');
        $loader->add_filter('woocommerce_product_single_add_to_cart_text', $this, 'change_add_to_cart_text_for_product');

        $loader->add_ajax_action('ajax_redirect_to_editor', $this, 'ajax_redirect_to_editor');
        $loader->add_ajax_nopriv_action('ajax_redirect_to_editor', $this, 'ajax_redirect_to_editor');
    }

    public function enqueue_styles(): void
    {
        //enqueue CSS and JS scripts
    }

    public function enqueue_ajax(): void
    {
        wp_enqueue_script('pwp-ajax-add-to-cart', plugins_url('js/add-to-cart.js', __FILE__), array('jquery'), rand(0, 2000), true);
        wp_localize_script(
            'pwp-ajax-add-to-cart',
            'ajax_redirect_to_editor_object',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('pwp_add_to_cart_nonce')
            )
        );
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

    public function ajax_redirect_to_editor(): void
    {
        $client = new PWP_editor_client('deveditor.peleman.com');

        $variant = wc_get_product((int)sanitize_text_field($_GET['variant']));
        $template_id = $variant->get_meta('template_id', true, 'view');
        $variant_id = $variant->get_meta('variant_code', true, 'view');

        $templateIsValid = str_contains($template_id, 'tpl');
        $variantIsValid = str_contains($variant_id, 'var');

        $content_file_id = sanitize_text_field($_GET['content']);

        if (!$templateIsValid || !$variantIsValid) {
            wp_send_json(array(
                'status' => 'error',
                'message' => 'variant does not have proper template data!',
            ));
            return;
        }
        $language = 'en';

        // $destination = $client->get_new_project_url($template_id, $variant_id, $language);
        $destination = 'https://deveditor.peleman.com/?projecturl=pie/projects/625e933128f37/var133714.json';
        wp_send_json(array(
            'status' => 'success',
            'message' => 'all is well',
            'isCustomizable' => true,
            'destinationUrl' => $destination,
        ), 200);
        return;
    }
}
