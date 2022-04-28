<?php

declare(strict_types=1);

namespace PWP\includes\loaders;

use PWP\includes\API\endpoints\PWP_I_Endpoint;

class PWP_Endpoint_Loader implements PWP_ILoader
{
    private string $namespace;
    private PWP_I_Endpoint $endpoint;
    
    public function __construct(string $namespace, PWP_I_Endpoint $endpoint)
    {
        $this->namespace = $namespace;
        $this->endpoint = $endpoint;
    }
    final public function register()
    {
        register_rest_route(
            $this->namespace,
            $this->endpoint->get_path(),
            array(
                'args' => $this->endpoint->get_arguments(),
                'callback' => $this->endpoint->get_callback(),
                'methods' => $this->endpoint->get_methods(),
                'permission_callback' => $this->endpoint->get_permission_callback(),
            )
        );
    }
}
