<?php

declare(strict_types=1);

namespace PWP\includes\API;

use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\includes\API\endpoints\PWP_I_Endpoint;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\hookables\PWP_IHookableComponent;
use PWP\includes\authentication\PWP_IApiAuthenticator;

abstract class PWP_Abstract_API_Channel implements PWP_IHookableComponent
{
    protected array $endpoints;
    protected string $namespace;
    protected string $rest_base;
    protected PWP_IApiAuthenticator $authenticator;

    public function __construct(string $namespace, string $rest_base = '', PWP_IApiAuthenticator $authenticator = null)
    {
        $this->namespace = $namespace;
        $this->rest_base = $rest_base;
        $this->authenticator = $authenticator ?: new PWP_Authenticator();
    }

    final protected function add_endpoint(PWP_I_Endpoint $endpoint): void
    {
        $this->endpoints[] = $endpoint;
    }

    final public function register_hooks(PWP_Plugin_Loader $loader): void
    {
        foreach ($this->endpoints as $endpoint) {
            $loader->add_API_Endpoint($this->namespace, $endpoint);
        }
    }
}
