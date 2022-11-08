<?php

declare(strict_types=1);

namespace PWP\includes\hookables\abstracts;

use PWP\includes\hookables\abstracts\I_Hookable_Component;
use PWP\includes\loaders\Plugin_Loader;

abstract class Abstract_Shortcode_Hookable implements I_Hookable_Component
{
    protected string $tag;

    protected const CALLBACK = 'shortcode_callback';

    public function __construct(string $tag)
    {
        $this->tag = $tag;
    }

    final public function register(): void
    {
        \add_shortcode($this->tag, array($this, self::CALLBACK));
    }

    public abstract function shortcode_callback(...$args): void;
}
