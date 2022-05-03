<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\PWP_IApiAuthenticator;

/**
 * abstract endpoint class for DELETE requests
 */
abstract class PWP_Abstract_DELETE_Endpoint extends PWP_EndpointController
{
    public function __construct(string $path, string $title, PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct($path, $title, $authenticator);
    }

    public function authenticate(\WP_REST_Request $request): bool
    {
        return $this->get_authenticator()->auth_delete_item($request);
    }

    public function get_arguments(): array
    {
        return [];
    }

    final public function get_methods(): string
    {
        return \WP_REST_Server::DELETABLE;
    }
}