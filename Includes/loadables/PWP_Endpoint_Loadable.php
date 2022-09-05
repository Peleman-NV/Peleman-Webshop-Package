<?php

declare(strict_types=1);

namespace PWP\includes\loadables;

use PWP\includes\API\endpoints\PWP_I_Endpoint;
use PWP\includes\hookables\abstracts\PWP_I_Hookable_Component;

class PWP_Endpoint_Loadable implements PWP_I_Hookable_Component
{
    private string $namespace;
    private PWP_I_Endpoint $endpoint;

    public function __construct(string $namespace, PWP_I_Endpoint $endpoint)
    {
        $this->namespace = $namespace;
        $this->endpoint = $endpoint;
    }
    final public function register(): void
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
