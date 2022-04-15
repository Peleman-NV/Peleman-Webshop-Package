<?php

declare(strict_types=1);

namespace PWP\includes;

use PWP\includes\API\PWP_API_Hookable;
use PWP\includes\hookables\PWP_IHookableComponent;
use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\publicPage\PWP_ProductPage;

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
    }

    public static function run()
    {
        $instance = new PWP_Plugin();

        $instance->initialize_hooks();
        $instance->register_hooks();

        do_action('PWP_plugin_loaded');
    }

    private function initialize_hooks()
    {
        if (is_admin()) {
            $this->add_admin_hookables();
        }

        $this->add_public_hookables();
    }

    private function add_admin_hookables(): void
    {
        //TODO: add admin hookables
    }

    private function add_public_hookables(): void
    {
        //TODO: add public hookables
        $this->add_hookable(new PWP_ProductPage($this->loader));
        $this->add_hookable(new PWP_API_Hookable('pwp/v1'));
    }

    private function add_hookable(PWP_IHookableComponent $component): void
    {
        $this->components[] = $component;
        $component->register($this->loader);
    }

    private function register_hooks()
    {
        $this->loader->register_hooks();
    }
}
