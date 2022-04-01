<?php

declare(strict_types=1);

namespace PPA\includes\loaders;

/**
 * wrapper class for Wordpress shortcodes
 */
final class PPA_ShortCodeLoader implements PPA_ILoader
{
    private string $tag;
    private object $component;
    private string $callback;

    public function __construct(string $tag, object $component, string $callback)
    {
        $this->tag = $tag;
        $this->object = $component;
        $this->callback = $callback;
    }

    final public function register()
    {
        \add_shortcode(
            $this->tag,
            array($this->component, $this->callback)
        );
    }
}
