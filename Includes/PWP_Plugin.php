<?php

declare(strict_types=1);

namespace PWP\includes;

use PWP\adminPage\hookables\PWP_Admin_Notice_Poster;
use PWP\includes\API\PWP_API_Hookable;
use PWP\includes\hookables\PWP_IHookableComponent;
use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\includes\utilities\PWP_Admin_Notice;
use PWP\publicPage\PWP_Public_Product_Page;

defined('ABSPATH') || exit;

if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . '/wp-admin/includes/plugin.php');
}

class PWP_Plugin
{
    private PWP_Plugin_Loader $loader;
    private string $version;
    private string $plugin_name;
    private PWP_Admin_Notice_Poster $noticePoster;

    private array $components;

    private function __construct()
    {

        $this->version = defined('PWP_VERSION') ? PWP_VERSION : '1.0.0';
        $this->plugin_name = 'Peleman Webshop Package';
        $this->loader = new PWP_Plugin_Loader();
        $this->components = array();
        $this->noticePoster = new PWP_Admin_Notice_Poster();

        if (!$this->check_requirements()) {
            return;
        }

        if (is_admin()) {
            //TODO: add admin hookables
            $this->add_hookable($this->noticePoster);
        }

        //TODO: add public hookables
        $this->add_hookable(new PWP_Public_Product_Page());
        $this->add_hookable(new PWP_API_Hookable('pwp/v1'));
    }

    public static function run()
    {
        $instance = new PWP_Plugin();

        $instance->register_hookables();

        do_action('PWP_plugin_loaded');
    }

    private function add_hookable(PWP_IHookableComponent $component): void
    {
        $this->components[] = $component;
        $component->register_hooks($this->loader);
    }

    private function register_hookables()
    {
        $this->loader->register_hooks();
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
