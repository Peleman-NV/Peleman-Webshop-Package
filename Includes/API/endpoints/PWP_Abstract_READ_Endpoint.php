<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\API\endpoints\PWP_EndpointController;

/**
 * abstract endpoint class for GET requests without a parameter
 * will try to find all items that match the request GET parameters
 */
abstract class PWP_Abstract_READ_Endpoint extends PWP_EndpointController
{

    public function __construct(string $path, string $title, PWP_Authenticator $authenticator)
    {
        parent::__construct($path, $title, $authenticator);
    }

    public function authenticate(\WP_REST_Request $request): bool
    {
        return $this->get_authenticator()->auth_get_items($request);
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
