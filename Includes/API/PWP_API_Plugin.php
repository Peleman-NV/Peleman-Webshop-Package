<?php

declare(strict_types=1);

namespace PWP\includes\API;

use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\includes\API\endpoints\PWP_Test_Endpoint;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\traits\PWP_Hookable_Parent_Trait;
use PWP\includes\hookables\PWP_I_Hookable_Component;

use PWP\includes\API\endpoints\categories\PWP_Categories_FIND_Endpoint;
use PWP\includes\API\endpoints\categories\PWP_Categories_READ_Endpoint;
use PWP\includes\API\endpoints\categories\PWP_Categories_BATCH_Endpoint;
use PWP\includes\API\endpoints\categories\PWP_Categories_CREATE_Endpoint;
use PWP\includes\API\endpoints\categories\PWP_Categories_DELETE_Endpoint;
use PWP\includes\API\endpoints\categories\PWP_Categories_UPDATE_Endpoint;

/**
 * overarching class which contains and handles the creation/registering of API Channels
 */
class PWP_API_Plugin implements PWP_I_Hookable_Component
{
    use PWP_Hookable_Parent_Trait;

    private string $namespace;
    /**
     * @var PWP_Abstract_API_Channel[]
     */
    private array $channels;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
        $authenticator = new PWP_Authenticator();
        // $this->add_child_hookable(new PWP_API_Categories_Channel($this->namespace));

        $this->add_child_hookable(new PWP_Test_Endpoint($authenticator));
        $this->add_child_hookable(new PWP_Categories_BATCH_Endpoint($this->namespace, "/categories", $authenticator));
        $this->add_child_hookable(new PWP_Categories_CREATE_Endpoint($this->namespace, "/categories", $authenticator));
        $this->add_child_hookable(new PWP_Categories_READ_Endpoint($this->namespace, "/categories", $authenticator));
        $this->add_child_hookable(new PWP_Categories_FIND_Endpoint($this->namespace, "/categories", $authenticator));
        $this->add_child_hookable(new PWP_Categories_UPDATE_Endpoint($this->namespace, "/categories", $authenticator));
        $this->add_child_hookable(new PWP_Categories_DELETE_Endpoint($this->namespace, "/categories", $authenticator));
    }

    public function register_hooks(PWP_Plugin_Loader $loader): void
    {
        $this->register_child_hooks($loader);
    }
}
