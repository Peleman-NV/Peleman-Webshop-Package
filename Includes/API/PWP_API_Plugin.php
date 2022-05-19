<?php

declare(strict_types=1);

namespace PWP\includes\API;

use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\traits\PWP_Hookable_Parent_Trait;
use PWP\includes\hookables\PWP_I_Hookable_Component;

use PWP\includes\API\endpoints\categories\PWP_API_Categories_Channel;

/**
 * overarching class which contains and handles the creation/registering of API Channels
 */
class PWP_API_Plugin implements PWP_I_Hookable_Component
{
    use PWP_Hookable_Parent_Trait;

    private string $namespace;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
        $authenticator = new PWP_Authenticator();

        $this->add_child_hookable(new PWP_API_Categories_Channel($this->namespace, '', $authenticator));
    }

    public function register_hooks(PWP_Plugin_Loader $loader): void
    {
        $this->register_child_hooks($loader);
    }
}
