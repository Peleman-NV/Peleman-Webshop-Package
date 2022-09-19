<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;
/**
 * abstract endpoint class for POST requests
 */
abstract class PWP_Abstract_CREATE_Endpoint extends PWP_Endpoint_Controller
{
    public function authenticate(\WP_REST_Request $request): bool
    {
        return $this->get_authenticator()->auth_post_item($request);
    }

    final public function get_methods(): string
    {
        return \WP_REST_Server::CREATABLE;
    }

    public function get_arguments(): array
    {
        return $this->get_endpoint_args_for_item_schema();
    }

    public function get_schema(): array
    {
        return [];
    }
}
