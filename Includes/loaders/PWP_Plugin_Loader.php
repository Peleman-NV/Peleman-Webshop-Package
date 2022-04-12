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

    /**
     * Add Ajax action to loader. Method automatically adds 'wp_ajax_' prefix to hook name. For logged-in users
     *
     * @param string $hook 
     * @param object $component
     * @param string $callback
     * @param integer $priority
     * @param integer $accepted_args
     * @return void
     */
    public function add_ajax_action(string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1): void
    {
        $this->loaders[] = new PWP_Action_Loader("wp_ajax_{$hook}", $component, $callback, $priority, $accepted_args);
    }

    /**
     * Add Ajax action to loader. Method automatically adds 'wp_ajax_nopriv' prefix to hook name. For non-logged-in users.
     *
     * @param string $hook 
     * @param object $component
     * @param string $callback
     * @param integer $priority
     * @param integer $accepted_args
     * @return void
     */
    public function add_ajax_nopriv_action(string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1): void
    {
        $this->loaders[] = new PWP_Action_Loader("wp_ajax_nopriv_{$hook}", $component, $callback, $priority, $accepted_args);
    }

    /**
     * Add admin post action to loader. method automatically ads 'admin_post' prefix to hook name.
     *
     * @param string $hook
     * @param object $component
     * @param string $callback
     * @param integer $priority
     * @param integer $accepted_args
     * @return void
     */
    public function add_admin_post_action(string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1): void
    {
        $this->loaders[] = new PWP_Action_Loader("admin_post_{$hook}", $component, $callback, $priority, $accepted_args);
    }

    /**
     * Add admin post action to loader. method automatically ads 'admin_post' prefix to hook name. for non-logged-in users.
     *
     * @param string $hook
     * @param object $component
     * @param string $callback
     * @param integer $priority
     * @param integer $accepted_args
     * @return void
     */
    public function add_admin_post_nopriv_action(string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1): void
    {
        $this->loaders[] = new PWP_Action_Loader("admin_post_nopriv_{$hook}", $component, $callback, $priority, $accepted_args);
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
