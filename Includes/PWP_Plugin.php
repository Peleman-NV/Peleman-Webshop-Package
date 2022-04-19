<?php

declare(strict_types=1);

namespace PWP\includes;

use PWP\includes\API\PWP_API_Hookable;
use PWP\includes\hookables\PWP_IHookableComponent;
use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\publicPage\PWP_Public_Product_Page;

defined('ABSPATH') || exit;

class PWP_Plugin
{
    private PWP_Plugin_Loader $loader;
    private string $version;
    private string $plugin_name;

    private array $components;

    private function __construct()
    {
        $this->version = defined('PWP_VERSION') ? PWP_VERSION : '1.0.0';
        $this->plugin_name = 'Peleman Webshop Package';
        $this->loader = new PWP_Plugin_Loader();
        $this->components = array();

        if (is_admin()) {
            //TODO: add admin hookables
        }

        //TODO: add public hookables
        $this->add_hookable(new PWP_Public_Product_Page($this->loader));
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
}
