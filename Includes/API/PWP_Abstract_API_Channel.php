<?php

declare(strict_types=1);

namespace PWP\includes\API;

use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\includes\API\endpoints\PWP_I_Endpoint;
use PWP\includes\hookables\abstracts\PWP_I_Hookable_Component;
use PWP\includes\authentication\PWP_I_Api_Authenticator;

/**
 * abstract class for the handling and registering of API Endpoints.
 */
class PWP_Abstract_API_Channel implements PWP_I_Hookable_Component
{
    protected PWP_Channel_Definition $definition;
    protected array $endpoints;
    protected PWP_I_Api_Authenticator $authenticator;

    public function __construct(string $namespace, string $title, string $rest_base, PWP_I_Api_Authenticator $authenticator)
    {
        $this->definition = new PWP_Channel_Definition($namespace, $title, $rest_base);
        $this->authenticator = $authenticator;
    }

    final protected function register_endpoint(PWP_I_Endpoint $endpoint): void
    {
        $this->endpoints[] = $endpoint;
    }

    final public function register(): void
    {
        foreach ($this->endpoints as $endpoint) {
            $endpoint->register();
        }
    }

    final public function get_definition(): PWP_Channel_Definition
    {
        return $this->definition;
    }
}
