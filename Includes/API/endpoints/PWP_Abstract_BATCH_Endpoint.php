<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;
use WP_REST_Request;

/**
 * abstract endpoint class for BATCH requests
 * allow a user to upload an array of data, and CREATE/UPDATE/DELETE multiple items in one call
 */
abstract class PWP_Abstract_BATCH_Endpoint extends PWP_EndpointController
{

    public function __construct(string $path, string $title, PWP_Authenticator $authenticator)
    {
        parent::__construct($path . '/batch', $title, $authenticator);
    }

    public function authenticate(WP_REST_Request $request): bool
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
        // return $this->get_authenticator()->auth_batch_items($request);
    }

    public function get_arguments(): array
    {
        return [];
    }

    final public function get_methods(): string
    {
        return \WP_REST_Server::EDITABLE;
    }
}
