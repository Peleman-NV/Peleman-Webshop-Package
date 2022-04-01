<?php

declare(strict_types=1);

namespace PPA\includes;

use PPA\includes\PPA_PluginLoader;

defined('ABSPATH') || exit;

class PPA_plugin
{
    private PPA_PluginLoader $loader;
    private string $plugin_name;
    private string $version;

    private function __construct()
    {
        $this->version = defined('PPA_VERSION') ? PPA_VERSION : '1.0.0';
        $this->plugin_name = 'Peleman Product API';

        $this->loader = new PPA_PluginLoader();
        $this->register_admin_hooks();
        $this->register_public_hooks();
    }
    public static function run()
    {
        $instance = new self();

        $instance->register_admin_hooks();
        $instance->register_public_hooks();

        do_action('PPA_Loaded');
    }

    private function register_admin_hooks()
    {
        //TODO: register admin hooks
    }

    private function register_public_hooks()
    {
        //TODO: register public hooks
    }

    private function register_api()
    {
        //TODO: register REST API
    }
}
