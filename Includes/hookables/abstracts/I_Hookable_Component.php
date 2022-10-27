<?php

declare(strict_types=1);

namespace PWP\includes\hookables\abstracts;

use PWP\includes\loaders\Plugin_Loader;

/**
 * interface for objects to register hooks, actions and filters.
 */
interface I_Hookable_Component
{
    /**
     * register actions and filters for this class
     */
    public function register(): void;
}
