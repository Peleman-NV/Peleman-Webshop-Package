<?php

declare(strict_types=1);

namespace PWP\includes\traits;

use PWP\includes\hookables\PWP_I_Hookable_Component;
use PWP\includes\loaders\PWP_Plugin_Loader;

trait PWP_Hookable_Parent_Trait
{
    /**
     * array of PWP_I_Hookable_Component objects
     *
     * @var PWP_I_Hookable_Component[]
     */
    public array $hookables;

    public function __construct()
    {
        $this->hookables = array();
    }

    public function add_hookable(PWP_I_Hookable_Component $hookable): void
    {
        $this->hookables[] = $hookable;
    }

    public function remove_child_hookable(PWP_I_Hookable_Component $hookable): void
    {
        unset($this->hookables[$hookable]);
    }
    public function register_hooks(PWP_Plugin_Loader $loader): void
    {
        foreach ($this->hookables as $hookable) {
            $hookable->register_hooks($loader);
        }
    }
}
