<?php

declare(strict_types=1);

namespace PWP\includes;

use PWP\includes\API\PWP_API_Plugin;
use PWP\adminPage\PWP_Parent_Custom_Fields;
use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\publicPage\PWP_Public_Product_Page;
use PWP\adminPage\PWP_Variable_Custom_Fields;
use PWP\includes\traits\PWP_Hookable_Parent_Trait;
use PWP\adminPage\hookables\PWP_Admin_Notice_Poster;
use PWP\includes\hookables\PWP_I_Hookable_Component;

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
            $this->add_hookable(new PWP_Variable_Custom_Fields());
            // $this->add_hookable(new PWP_Parent_Custom_Fields());
        }

        /*  ADD PUBLIC HOOKABLES HERE */

        $this->add_hookable(new PWP_Public_Product_Page());
        $this->add_hookable(new PWP_API_Plugin('pwp/v1'));

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
