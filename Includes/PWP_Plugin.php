<?php

declare(strict_types=1);

namespace PWP\includes;

use PWP\includes\traits\PWP_Hookable_Parent_Trait;
use PWP\includes\hookables\abstracts\PWP_I_Hookable_Component;

use PWP\includes\API\PWP_API_Plugin;
use PWP\includes\loaders\PWP_Plugin_Loader;

use PWP\publicPage\PWP_Add_Customizable_To_Cart;
use PWP\publicPage\PWP_Public_Product_Page;

use PWP\adminPage\hookables\PWP_Admin_Notice_Poster;
use PWP\adminPage\hookables\PWP_Variable_Product_Custom_Fields;
use PWP\adminPage\hookables\PWP_Save_Variable_Custom_Fields;
use PWP\includes\API\endpoints\PWP_TEST_OAuth2_Client_Endpoint;
use PWP\includes\API\PWP_Channel_Definition;
use PWP\includes\hookables\PWP_Add_PDF_Contents_To_Cart;
use PWP\includes\hookables\PWP_Add_Product_Fields;
use PWP\includes\hookables\PWP_Add_Project_Data_To_Cart;
use PWP\includes\hookables\PWP_Display_Project_Data_In_Cart;
use PWP\publicPage\PWP_Add_To_Cart;

if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . '/wp-admin/includes/plugin.php');
}

defined('ABSPATH') || exit;

class PWP_Plugin implements PWP_I_Hookable_Component
{
    use PWP_Hookable_Parent_Trait;

    private PWP_Plugin_Loader $loader;
    private string $version;
    private string $plugin_name;
    private PWP_Admin_Notice_Poster $noticePoster;

    private function __construct()
    {
        $this->version = defined('PWP_VERSION') ? PWP_VERSION : '1.0.0';
        $this->plugin_name = 'Peleman Webshop Package';
        $this->loader = new PWP_Plugin_Loader();
        $this->noticePoster = new PWP_Admin_Notice_Poster();

        if (!$this->check_if_requirements_met()) {
            return;
        }

        if (is_admin()) {

            /*  ADD ADMIN MENU HOOKABLES HERE */
            $this->add_hookable($this->noticePoster);
            $this->add_hookable(new PWP_Variable_Product_Custom_Fields());
            $this->add_hookable(new PWP_Save_Variable_Custom_Fields());
            //TODO: component in progress
            // $this->add_hookable(new PWP_Parent_Custom_Fields());
        }

        /*  ADD PUBLIC PAGE HOOKABLES HERE */
        $this->add_hookable(new PWP_Public_Product_Page());
        $this->add_hookable(new PWP_Add_To_Cart());

        $this->add_hookable(new PWP_API_Plugin('pwp/v1'));
        $this->add_hookable(new PWP_TEST_OAuth2_Client_Endpoint());

        /* PIE product hookables */
        $this->add_hookable(new PWP_Add_Product_Fields());
        // $this->add_hookable(new PWP_Add_PDF_Contents_To_Cart());
        $this->add_hookable(new PWP_Add_Project_Data_To_Cart());
        $this->add_hookable(new PWP_Display_Project_Data_In_Cart());

        /* REGISTER CHILD HOOKS WITH LOADER */
        $this->register_hooks($this->loader);
    }

    final public static function run()
    {
        $instance = new PWP_Plugin();
        $instance->register_components();
        do_action('PWP_plugin_loaded');
    }

    private function register_components()
    {
        $this->loader->register_hooks_to_wp();
    }

    private function check_if_requirements_met(): bool
    {
        if (!\is_plugin_active('woocommerce/woocommerce.php')) {
            $this->noticePoster->new_warning_notice("{$this->plugin_name} needs Woocommerce to function properly!", true);
        }

        if (!\is_plugin_active('sitepress-multilingual-cms/sitepress.php')) {
            $this->noticePoster->new_error_notice("{$this->plugin_name} needs WPML to function properly", true);
        }

        return true;
    }
}
