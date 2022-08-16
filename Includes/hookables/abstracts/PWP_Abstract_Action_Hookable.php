<?php

declare(strict_types=1);

namespace PWP\includes\hookables\abstracts;

use PWP\includes\hookables\abstracts\PWP_I_Hookable_Component;
use PWP\includes\loaders\PWP_Plugin_Loader;

abstract class PWP_Abstract_Action_Hookable implements PWP_I_Hookable_Component
{
    protected string $hook;
    protected string $callback;
    protected int $priority;
    protected int $accepted_args;

    /**
     * @param string $hook WP Hook to hook onto
     * @param string $callback name of the method that will be called
     * @param integer $priority execution priority of this component
     * @param integer $accepted_args amount of arguments this method's callback accepts
     */
    public function __construct(string $hook, string $callback, int $priority = 10, $accepted_args = 1)
    {
        $this->hook = $hook;
        $this->callback = $callback;
        $this->priority = $priority;
        $this->accepted_args = $accepted_args;
    }

    public function register_hooks(PWP_Plugin_Loader $loader): void
    {
        $loader->add_action(
            $this->hook,
            $this,
            $this->callback,
            $this->priority,
            $this->accepted_args
        );
    }
}
