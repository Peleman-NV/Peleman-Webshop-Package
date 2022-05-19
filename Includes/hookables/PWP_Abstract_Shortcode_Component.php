<?php

declare(strict_types=1);

namespace PWP\includes\hookables;

use PWP\includes\hookables\PWP_I_Hookable_Component;
use PWP\includes\loaders\PWP_Plugin_Loader;

abstract class PWP_Abstract_Shortcode_Component implements PWP_I_Hookable_Component
{
    protected string $tag;

    protected const CALLBACK = 'shortcode_callback';

    public function __construct(string $tag)
    {
        $this->tag = $tag;
    }

    final public function register_hooks(PWP_Plugin_Loader $loader): void
    {
        $loader->add_shortcode(
            $this->tag,
            $this,
            SELF::CALLBACK,
        );
    }

    public abstract function shortcode_callback(...$args);
}