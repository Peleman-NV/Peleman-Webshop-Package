<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\products;

use PWP\includes\API\endpoints\PWP_Abstract_CREATE_Endpoint;
use WP_REST_Request;
use WP_REST_Response;

class PWP_Products_CREATE_Endpoint extends PWP_Abstract_CREATE_Endpoint
{
    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        switch ($request['type']) {
            case 'variable':
                break;
            case 'variant':
                break;
            default:
            case 'simple':
                break;
        }
        return new WP_REST_Response('testing endpoint for product creation', 200);
    }
}
