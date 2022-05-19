<?php

declare(strict_types=1);

namespace PWP\includes;

use PWP\adminPage\hookables\PWP_Admin_Notice_Poster;
use PWP\includes\API\PWP_API_Plugin;
use PWP\includes\hookables\PWP_I_Hookable_Component;
use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\includes\traits\PWP_Hookable_Parent_Trait;
use PWP\publicPage\PWP_Public_Product_Page;

if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . '/wp-admin/includes/plugin.php');
}

defined('ABSPATH') || exit;

class PWP_Plugin
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

        if (!$this->check_requirements()) {
            return;
        }

        if (is_admin()) {

            /*  ADD ADMIN MENU HOOKABLES HERE */

            $this->add_child_hookable($this->noticePoster);
        }

        /*  ADD PUBLIC HOOKABLES HERE */

        $this->add_child_hookable(new PWP_Public_Product_Page());

        $this->add_child_hookable(new PWP_API_Plugin('pwp/v1'));

        /* REGISTER CHILD HOOKS WITH LOADER */
        $this->register_child_hooks($this->loader);
    }

    final public static function run()
    {
        $instance = new PWP_Plugin();
        $instance->register_hookables();
        do_action('PWP_plugin_loaded');
    }

    private function register_hookables()
    {
        $this->loader->register_hooks_to_wp();
    }

    private function check_requirements(): bool
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
