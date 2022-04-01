<?php

declare(strict_types=1);

namespace PPA\includes;

use PPA\includes\hookables\PPA_IHookable;
use PPA\includes\loaders\PPA_Plugin_Loader;
use PPA\includes\hookables\PPA_API_Hookable;


defined('ABSPATH') || exit;

class PPA_plugin
{
    private PPA_Plugin_Loader $loader;
    private string $plugin_name;
    private string $version;

    private function __construct()
    {
        $this->version = defined('PPA_VERSION') ? PPA_VERSION : '1.0.0';
        $this->plugin_name = 'Peleman Product API';
        $this->loader = new PPA_Plugin_Loader();   
    }

    public static function run()
    {
        $instance = new self();

        $instance->initialize_hooks();
        $instance->register_hooks();

        do_action('ppa_plugin_loaded');
    }

    private function initialize_hooks()
    {
        $this->add_hookable(new PPA_API_Hookable('ppa/v1'));
    }

    private function register_hooks()
    {
        /**
         * @var PPA_Ihookable
         */
        foreach($this->components as $component)
        {
            $component->register($this->loader);
        }
    }

    private function add_hookable(PPA_IHookable $component): void
    {
        $this->components[] = $component;
    }
}
