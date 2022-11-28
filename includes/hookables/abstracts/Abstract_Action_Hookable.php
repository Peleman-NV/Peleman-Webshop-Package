<?php

declare(strict_types=1);

namespace PWP\includes\hookables\abstracts;

use PWP\includes\hookables\abstracts\I_Hookable_Component;
use PWP\includes\loaders\ILoader;
use PWP\includes\loaders\Plugin_Loader;

/**
 * Abstract observer class for implementing WP action hooks in an OOP fashion. 
 */
abstract class Abstract_Action_Hookable extends Abstract_Hookable
{
    final public function register(): void
    {
        foreach ($this->hooks as $hook) {
            \add_action(
                $hook->hook,
                array($this, $this->callback),
                $hook->priority,
                $this->accepted_args
            );
        }
    }
}
