<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\authentication\PWP_IApiAuthenticator;

class PWP_Customers_Endpoint extends PWP_Abstract_READ_Endpoint
{
    public function __construct(PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            "/customers",
            'customer',
            $this->authenticator = $authenticator
        );
    }

    final public function do_action(\WP_REST_Request $request): \WP_REST_Response
    {
        return new \WP_REST_Response("we're not quite there yet, but we will be soon!", 501);
    }
}
