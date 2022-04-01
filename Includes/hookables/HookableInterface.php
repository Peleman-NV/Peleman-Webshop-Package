<?php

declare(strict_types=1);

namespace PPA\includes\hookables;

use PPA\includes\Loader;
use PPA\includes\PPA_PluginLoader;

/**
 * interface for objects to register hooks, actions and filters.
 */
interface HookableInterface
{
    /**
     * register actions and filters for this class
     */
    public function register(PPA_PluginLoader $loader) : void;
}