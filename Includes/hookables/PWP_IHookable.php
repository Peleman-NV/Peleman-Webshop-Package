<?php

declare(strict_types=1);

namespace PWP\includes\hookables;

use PWP\includes\loaders\PWP_Plugin_Loader;

/**
 * interface for objects to register hooks, actions and filters.
 */
interface PWP_IHookable
{
    /**
     * register actions and filters for this class
     */
    public function register(PWP_Plugin_Loader $loader) : void;
}