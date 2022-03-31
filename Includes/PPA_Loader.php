<?php

declare(strict_types=1);

namespace PPA\includes;

class PPA_Loader
{

    private array $actions;
    private array $filters;
    private array $shortcodes;

    private const HOOK = 'hook';
    private const COMPONENT = 'comp';
    private const CALLBACK = 'call';
    private const PRIORITY = 'prio';
    private const ACCEPTED_ARGS = 'args';

    public function __construct()
    {
        $this->actions = array();
        $this->filters = array();
        $this->shortcodes = array();
    }

    public function add_action(string $hook, Object $component, string $callback, int $priority = 10, int $accepted_args = 1): void
    {
        $this->actions[] = self::to_array($hook, $component, $callback, $priority, $accepted_args);
    }

    public function add_filter(string $hook, Object $component, string $callback, int $priority = 10, int $accepted_args = 1): void
    {
        $this->filters[] = self::to_array($hook, $component, $callback, $priority, $accepted_args);
    }

    public function add_shortcode(string $tag, object $component, string $callback): void
    {
        $this->shortcodes[] = self::to_array($tag, $component, $callback);
    }

    final private function to_array(string $hook, Object $component, string $callback, int $priority = 10, int $accepted_args = 1): array
    {
        return array(
            self::HOOK => $hook,
            self::COMPONENT => $component,
            self::CALLBACK => $callback,
            self::PRIORITY => $priority,
            self::ACCEPTED_ARGS => $accepted_args
        );
    }

    final public function register_hooks(): void
    {
        foreach ($this->actions as $action) {
            \add_action(
                $action[self::HOOK],
                array($action[self::COMPONENT], $action[self::CALLBACK]),
                $action[self::PRIORITY],
                $action[self::ACCEPTED_ARGS]
            );
        }

        foreach ($this->filters as $filter) {
            \add_filter(
                $filter[self::HOOK],
                array($filter[self::COMPONENT], $filter[self::CALLBACK]),
                $filter[self::PRIORITY],
                $filter[self::ACCEPTED_ARGS]
            );
        }

        foreach($this->shortcodes as $shortcode)
        {
            \add_shortcode(
                $shortcode[self::HOOK],
                array($shortcode[self::COMPONENT], $shortcode[self::CALLBACK])
            );
        }
    }
}
