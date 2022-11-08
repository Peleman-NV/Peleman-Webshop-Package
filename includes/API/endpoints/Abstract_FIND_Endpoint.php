<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\API\endpoints\Endpoint_Controller;
/**
 * abstract endpoint class for GET requests WITHOUT a required parameter
 * will try to retrieve a singular result
 */
abstract class Abstract_FIND_Endpoint extends Endpoint_Controller
{
    public function authenticate(\WP_REST_Request $request): bool
    {
        return $this->get_authenticator()->auth_get_item($request);
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