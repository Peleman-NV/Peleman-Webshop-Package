<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\API\endpoints\Endpoint_Controller;

/**
 * abstract endpoint class for GET requests without a parameter
 * will try to find all items that match the request GET parameters
 */
abstract class Abstract_READ_Endpoint extends Endpoint_Controller
{
    public function authenticate(\WP_REST_Request $request): bool
    {
        return $this->get_authenticator()->auth_get_items($request);
    }

    final public function get_methods(): string
    {
        return \WP_REST_Server::READABLE;
    }

    public function get_schema(): array
    {
        return [];
    }
}
