<?php

declare(strict_types=1);

namespace PWP\includes\hookables\abstracts;

use PWP\includes\hookables\abstracts\PWP_I_Hookable_Component;
use PWP\includes\loaders\PWP_ILoader;
use PWP\includes\loaders\PWP_Plugin_Loader;

abstract class PWP_Abstract_Action_Hookable implements PWP_I_Hookable_Component
{
    protected array $hooks;
    protected string $callback;
    protected array $priorities;
    protected int $accepted_args;

    /**
     * @param string $hook WP Hook to hook onto
     * @param string $callback name of the method that will be called
     * @param integer $priority execution priority of this component
     * @param integer $accepted_args amount of arguments this method's callback accepts
     */
    public function __construct(string $hook, string $callback, int $priority = 10, $accepted_args = 1)
    {
        $myhook = array(
            'hook' => $hook,
            'priority' => $priority
        );
        $this->hooks[] = $myhook;
        $this->callback = $callback;
        $this->accepted_args = $accepted_args;
    }

    final public function register(): void
    {
        foreach ($this->hooks as $hook) {
            \add_action(
                $hook['hook'],
                array($this, $this->callback),
                $hook['priority'],
                $this->accepted_args
            );
        }
    }

    final public function add_hook(string $hook, int $priority = 10): void
    {
        $this->hooks[] = array(
            'hook' => $hook,
            'priority' => $priority
        );
    }
}
