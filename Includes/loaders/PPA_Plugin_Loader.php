<?php

declare(strict_types=1);

namespace PPA\includes\loaders;

use PPA\includes\loaders\PPA_Action_Loader;
use PPA\includes\loaders\PPA_Filter_Loader;
use PPA\includes\loaders\PPA_Shortcode_Loader;

class PPA_Plugin_Loader
{
    private array $actions;
    private array $filters;
    private array $shortcodes;

    public function __construct()
    {
        $this->loaders = array();
    }

    public function add_action(string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1): void
    {
        $this->loaders[] = new PPA_Action_Loader($hook, $component, $callback, $priority, $accepted_args);
    }

    public function add_filter(string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1): void
    {
        $this->loaders[] = new PPA_Filter_Loader($hook, $component, $callback, $priority, $accepted_args);
    }

    public function add_shortcode(string $tag, object $component, string $callback): void
    {
        $this->loaders[] = new PPA_Shortcode_Loader($tag, $component, $callback);
    }

    final public function register_hooks(): void
    {
        /**
         * @var PPA_ILoader;
         */
        foreach ($this->loaders as $loader) {
            $loader->register();
        }
    }
}
