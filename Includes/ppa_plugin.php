<?php

declare(strict_types=1);

namespace PPA\includes;

use PPA\includes\PPA_PluginLoader;

class PPA_plugin
{
    private PPA_PluginLoader $loader;
    private string $plugin_name;
    private string $version;

    public function __construct()
    {
        $this->version = defined('PPA_VERSION') ? PPA_VERSION : '1.0.0';
        $this->plugin_name = 'Peleman Product API';

        $this->loader = new PPA_PluginLoader();
        $this->register_admin_hooks();
        $this->register_public_hooks();
    }
    public function run()
    {
    }

    private function register_admin_hooks()
    {
    }

    private function register_PUBLIC_HOOKS()
    {
    }
}
