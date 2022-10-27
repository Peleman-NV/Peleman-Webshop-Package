<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;

/**
 * abstract endpoint class for BATCH requests
 * allow a user to upload an array of data, and CREATE/UPDATE/DELETE multiple items in one call
 */
abstract class Abstract_BATCH_Endpoint extends Endpoint_Controller
{
    public function authenticate(WP_REST_Request $request): bool
    {
        return $this->get_authenticator()->auth_batch_items($request);
    }

    final public function get_methods(): string
    {
        return \WP_REST_Server::EDITABLE;
    }

    public function get_schema(): array
    {
        return [];
    }
}
