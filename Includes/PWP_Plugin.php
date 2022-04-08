<?php

declare(strict_types=1);

namespace PWP\includes;

use PWP\includes\API\PWP_API_Hookable;
use PWP\includes\hookables\PWP_IHookable;
use PWP\includes\loaders\PWP_Plugin_Loader;

defined('ABSPATH') || exit;

class PWP_Plugin
{
    private PWP_Plugin_Loader $loader;
    private string $version;
    private string $plugin_name;

    private array $components;

    public function __construct()
    {
        $this->version = defined('PWP_VERSION') ? PWP_VERSION : '1.0.0';
        $this->plugin_name = 'Peleman Webshop Package';
        $this->loader = new PWP_Plugin_Loader();
        $this->components = array();
    }

    public function run()
    {
        $this->initialize_hooks();
        $this->register_hooks();

        do_action('PWP_plugin_loaded');
    }

    private function initialize_hooks()
    {
        $this->add_hookable(new PWP_API_Hookable('pwp/v1'));

        if(is_admin())
        {
            //load admin panel only hooks
        }
        //TODO: add the other hookable components
    }

    private function add_hookable(PWP_IHookable $component): void
    {
        $this->components[] = $component;
        $component->register($this->loader);
    }

    private function register_hooks()
    {
        $this->loader->register_hooks();
    }
}
