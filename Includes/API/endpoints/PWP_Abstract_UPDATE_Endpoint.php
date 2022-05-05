<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\PWP_IApiAuthenticator;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;

/**
 * abstract endpoint class for PUT/PATCH requests
 * 
 */
abstract class PWP_Abstract_UPDATE_Endpoint extends PWP_EndpointController
{
    public function __construct(string $path, string $title, PWP_IApiAuthenticator $authenticator)
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
        return 'PUT, PATCH';
    }
}
