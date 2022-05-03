<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\API\endpoints\PWP_EndpointController;
use PWP\includes\authentication\PWP_IApiAuthenticator;

/**
 * abstract endpoint class for GET requests WITHOUT a required parameter
 * will try to retrieve a singular result
 */
abstract class PWP_Abstract_FIND_Endpoint extends PWP_EndpointController
{

    public function __construct(string $path, string $title, PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct($path, $title, $authenticator);
    }

    public function authenticate(\WP_REST_Request $request): bool
    {
        return $this->get_authenticator()->auth_get_item($request);
    }

    public function get_arguments(): array
    {
        return [];
    }

    final public function get_methods(): string
    {
        return \WP_REST_Server::READABLE;
    }
}