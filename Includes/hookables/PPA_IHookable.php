<?php

declare(strict_types=1);

namespace PPA\includes\hookables;

use PPA\includes\loaders\PPA_Plugin_Loader;

/**
 * interface for objects to register hooks, actions and filters.
 */
interface PPA_IHookable
{
    /**
     * register actions and filters for this class
     */
    public function register(PPA_Plugin_Loader $loader) : void;
}