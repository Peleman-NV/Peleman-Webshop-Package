<?php

declare(strict_types=1);

namespace PWP\includes;

use PWP\includes\hookables\PWP_IHookable;
use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\includes\hookables\PWP_API_Hookable;


defined('ABSPATH') || exit;

class PWP_plugin
{
    private PWP_Plugin_Loader $loader;
    private string $version;
    private string $plugin_name;

    private array $components;

    public function __construct()
    {
        $this->version = defined('PWP_VERSION') ? PWP_VERSION : '1.0.0';
        $this->plugin_name = 'Peleman Product API';
        $this->loader = new PWP_Plugin_Loader();
        $this->components = array();
    }

    public function run()
    {
        $this->initialize_hooks();
        $this->register_hooks();

        do_action('ppa_plugin_loaded');
    }

    private function initialize_hooks()
    {
        $this->add_hookable(new PWP_API_Hookable('ppa/v1'));
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
