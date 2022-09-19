<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

/**
 * abstract endpoint class for PUT/PATCH requests
 * 
 */
abstract class PWP_Abstract_UPDATE_Endpoint extends PWP_Endpoint_Controller
{
    public function authenticate(\WP_REST_Request $request): bool
    {
        return $this->get_authenticator()->auth_get_items($request);
    }

    final public function get_methods(): string
    {
        return 'PUT, PATCH';
    }

    public function get_schema(): array
    {
        return [];
    }
}
