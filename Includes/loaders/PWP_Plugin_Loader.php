<?php

declare(strict_types=1);

namespace PWP\includes\loaders;

use PWP\includes\loaders\PWP_Action_Loader;
use PWP\includes\loaders\PWP_Filter_Loader;
use PWP\includes\loaders\PWP_Shortcode_Loader;

class PWP_Plugin_Loader
{
    private array $loaders;

    public function __construct()
    {
        $this->loaders = array();
    }

    public function add_action(string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1): void
    {
        $this->loaders[] = new PWP_Action_Loader($hook, $component, $callback, $priority, $accepted_args);
    }

    public function add_filter(string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1): void
    {
        $this->loaders[] = new PWP_Filter_Loader($hook, $component, $callback, $priority, $accepted_args);
    }

    public function add_shortcode(string $tag, object $component, string $callback): void
    {
        $this->loaders[] = new PWP_Shortcode_Loader($tag, $component, $callback);
    }

    final public function register_hooks(): void
    {
        /**
         * @var PWP_ILoader;
         */
        foreach ($this->loaders as $loader) {
            $loader->register();
        }
    }
}
