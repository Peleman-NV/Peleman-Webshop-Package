<?php

declare(strict_types=1);

namespace PWP\includes\hookables\abstracts;

use PWP\includes\hookables\abstracts\PWP_I_Hookable_Component;
use PWP\includes\loaders\PWP_Plugin_Loader;

abstract class PWP_Abstract_Shortcode_Hookable implements PWP_I_Hookable_Component
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
