<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\PWP_IApiAuthenticator;
use WP_REST_Request;

/**
 * abstract endpoint class for BATCH requests
 * allow a user to upload an array of data, and CREATE/UPDATE/DELETE multiple items in one call
 */
abstract class PWP_Abstract_BATCH_Endpoint extends PWP_EndpointController
{

    public function __construct(string $path, string $title, PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct($path, $title, $authenticator);
    }

    public function authenticate(WP_REST_Request $request): bool
    {
        return $this->get_authenticator()->auth_batch_items($request);
    }

    abstract public function get_arguments(): array;

    final public function get_methods(): string
    {
        return \WP_REST_Server::EDITABLE;
    }
}
