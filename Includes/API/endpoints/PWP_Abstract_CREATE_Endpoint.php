<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\PWP_IApiAuthenticator;

/**
 * abstract endpoint class for POST requests
 */
abstract class PWP_Abstract_CREATE_Endpoint extends PWP_EndpointController
{

    public function __construct(string $path, string $title, PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct($path, $title, $authenticator);
    }

    public function authenticate(\WP_REST_Request $request): bool
    {
        return $this->get_authenticator()->auth_post_item($request);
    }

    public function get_arguments(): array
    {
        return [];
    }

    final public function get_methods(): string
    {
        return \WP_REST_Server::CREATABLE;
    }
}