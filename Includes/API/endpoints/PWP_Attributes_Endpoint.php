<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\authentication\PWP_IApiAuthenticator;
use PWP\includes\handlers\PWP_Product_Attribute_Handler;

class PWP_Attributes_Endpoint extends PWP_EndpointController
{    
    public function __construct(string $namespace, PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            $namespace,
            $authenticator,
            "/attributes",
            'attribute'
        );
    }
    
    public function get_items(WP_REST_Request $request): WP_REST_Response
    {
        $handler = new PWP_Product_Attribute_Handler('');
        return new WP_REST_Response($handler->get_items());
    }

    public function get_item(WP_REST_Request $request): WP_REST_Response
    {
        $handler = new PWP_Product_Attribute_Handler('');
        return new WP_REST_Response($handler->get_item((int)$request['id']));
    }
}