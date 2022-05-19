<?php

declare(strict_types=1);

namespace PWP\includes;

use PWP\includes\hookables\PWP_I_Hookable_Component;
use PWP\includes\loaders\PWP_Plugin_Loader;

abstract class PWP_Abstract_Action_Component implements PWP_I_Hookable_Component
{
    protected string $hook;
    protected int $priority;
    protected int $accepted_args;
    protected const CALLBACK = 'action_callback';

    public function __construct(string $hook, int $priority = 10, $accepted_args = 1)
    {
        $this->hook = $hook;
        $this->priority = $priority;
        $this->accepted_args = $accepted_args;
    }

    final public function register_hook(PWP_Plugin_Loader $loader): void
    {
        $loader->add_action(
            $this->hook,
            $this,
            SELF::CALLBACK,
            $this->priority,
            $this->accepted_args
        );
    }

    public abstract function action_callback(...$args);
}
