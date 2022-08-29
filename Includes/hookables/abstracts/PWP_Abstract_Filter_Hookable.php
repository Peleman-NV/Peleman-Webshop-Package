<?php

declare(strict_types=1);

namespace PWP\includes\hookables\abstracts;

use PWP\includes\hookables\abstracts\PWP_I_Hookable_Component;
use PWP\includes\loadables\PWP_Plugin_Loader;

abstract class PWP_Abstract_Filter_Hookable implements PWP_I_Hookable_Component
{
    protected string $hook;
    protected string $callback;
    protected int $priority;
    protected int $accepted_args;

    public function __construct(string $hook, string $callback, int $priority = 10, $accepted_args = 1)
    {
        $this->hook = $hook;
        $this->priority = $priority;
        $this->accepted_args = $accepted_args;
        $this->callback = $callback;
    }

    final public function register_hooks(PWP_Plugin_Loader $loader): void
    {
        $loader->add_hookable($this);
    }

    final public function register(): void
    {
        \add_filter(
            $this->hook,
            array($this, $this->callback),
            $this->priority,
            $this->accepted_args
        );
    }
}
