<?php

declare(strict_types=1);

namespace PPA\includes;

use PPA\includes\loaders\PPA_ActionLoader;
use PPA\includes\loaders\PPA_FilterLoader;
use PPA\includes\loaders\PPA_ShortCodeLoader;

class PPA_PluginLoader
{
    private array $actions;
    private array $filters;
    private array $shortcodes;


    public function __construct()
    {
        /**
         * @var PPA_ActionLoader[]
         */
        $this->actions = array();
        /**
         * @var PPA_FilterLoader[]
         */
        $this->filters = array();
        /**
         * @var PPA_ShortCodeLoader[]
         */
        $this->shortcodes = array();
    }

    public function add_action(string $hook, Object $component, string $callback, int $priority = 10, int $accepted_args = 1): void
    {
        $this->actions[] = new PPA_ActionLoader($hook, $component, $callback, $priority, $accepted_args);
    }

    public function add_filter(string $hook, Object $component, string $callback, int $priority = 10, int $accepted_args = 1): void
    {
        $this->filters[] = new PPA_FilterLoader($hook, $component, $callback, $priority, $accepted_args);
    }

    public function add_shortcode(string $tag, object $component, string $callback): void
    {
        $this->shortcodes[] = new PPA_ShortCodeLoader($tag, $component, $callback);
    }

    final public function register_hooks(): void
    {
        /**
         * @var PPA_ActionLoader;
         */
        foreach ($this->actions as $action) {
            $action->register();
        }

        /**
         * @var PPA_FilterLoader;
         */
        foreach ($this->filters as $filter) {
            $filter->register();
        }

        /**
         * @var PPA_ShortCodeLoader;
         */
        foreach ($this->shortcodes as $shortcode) {
            $shortcode->register();
        }
    }
}
