<?php

declare(strict_types=1);

namespace PPA\includes;

class PPA_plugin
{
    private PPA_Loader $loader;
    private string $plugin_name;
    private string $version;

    public function __construct()
    {
        $this->version = defined('PPA_VERSION')? PPA_VERSION : '1.0.0'; 
        $this->loader = new PPA_Loader();
    }
    public function run()
    {
    }
}
