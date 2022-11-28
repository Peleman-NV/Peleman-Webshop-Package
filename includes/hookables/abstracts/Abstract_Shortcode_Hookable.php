<?php

declare(strict_types=1);

namespace PWP\includes\hookables\abstracts;

use PWP\includes\hookables\abstracts\I_Hookable_Component;

/**
 * Abstract observer class for implementing WP sortcodes in an OOP fashion. 
 */
abstract class Abstract_Shortcode_Hookable implements I_Hookable_Component
{
    /**
     * Shortcode tag
     *
     * @var string
     */
    protected string $tag;

    protected const CALLBACK = 'shortcode_callback';

    /**
     * Abstract observer class for a WP shortcode
     *
     * @param string $tag
     */
    public function __construct(string $tag)
    {
        $this->tag = $tag;
    }

    final public function register(): void
    {
        \add_shortcode($this->tag, array($this, self::CALLBACK));
    }

    /**
     * Method to run when hook is called
     *
     * @param mixed ...$args
     * @return void
     */
    public abstract function shortcode_callback(...$args): void;
}
