<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\authentication\PWP_IApiAuthenticator;

class PWP_Menus_Endpoint extends PWP_EndpointController
{
    public function __construct()
    {
        parent::__construct(
            "/menus",
            'menu'
        );
    }
}