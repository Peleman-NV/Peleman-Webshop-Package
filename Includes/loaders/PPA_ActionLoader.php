<?php

declare(strict_types=1);

namespace PPA\includes\loaders;

/**
 * wrapper class for Wordpress action hooks
 */
final class PPA_ActionLoader implements PPA_ILoader
{
    private string $name;
    private object $component;
    private string $callback;
    private int $priority;
    private int $accepted_args;

    public function __construct(string $name, object $component, string $callback, int $priority = 1, int $accepted_args = 1)
    {
        $this->name = $name;
        $this->object = $component;
        $this->callback = $callback;
        $this->priority = $priority;
        $this->accepted_args = $accepted_args;
    }

    final public function register()
    {
        \add_action(
            $this->name,
            array($this->component, $this->callback),
            $this->priority,
            $this->accepted_args
        );
    }
}
