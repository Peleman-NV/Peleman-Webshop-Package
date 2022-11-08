<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;
/**
 * abstract endpoint class for DELETE requests
 */
abstract class Abstract_DELETE_Endpoint extends Endpoint_Controller
{
    public function authenticate(\WP_REST_Request $request): bool
    {
        return $this->get_authenticator()->auth_delete_item($request);
    }

    final public function get_methods(): string
    {
        return \WP_REST_Server::DELETABLE;
    }

    public function get_schema(): array
    {
        return [];
    }
}