<?php

declare(strict_types=1);

namespace PWP\includes\hookables\abstracts;

use PWP\includes\hookables\abstracts\I_Hookable_Component;
use PWP\includes\loadables\Plugin_Loader;

/**
 * Abstract observer class for implementing WP filter hooks in an OOP fashion. 
 */
abstract class Abstract_Filter_Hookable extends Abstract_hookable
{
    final public function register(): void
    {
        foreach ($this->hooks as $hook) {
            \add_filter(
                $hook->hook,
                array($this, $this->callback),
                $hook->priority,
                $this->accepted_args
            );
        }
    }
}
