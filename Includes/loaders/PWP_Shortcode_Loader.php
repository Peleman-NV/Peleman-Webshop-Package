<?php

declare(strict_types=1);

namespace PWP\includes\loaders;

/**
 * wrapper class for Wordpress shortcodes
 */
final class PWP_Shortcode_Loader implements PWP_ILoader
{
    private string $tag;
    private object $component;
    private string $callback;

    public function __construct(string $tag, object $component, string $callback)
    {
        $this->tag = $tag;
        $this->component = $component;
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
