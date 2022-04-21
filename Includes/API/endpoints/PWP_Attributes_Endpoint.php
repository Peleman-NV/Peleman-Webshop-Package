<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\API\PWP_API_Logger;
use PWP\includes\utilities\PWP_Null_Logger;
use PWP\includes\authentication\PWP_IApiAuthenticator;
use PWP\includes\handlers\PWP_IHandler;
use PWP\includes\handlers\PWP_Product_Attribute_Handler;

class PWP_Attributes_Endpoint extends PWP_EndpointController
{
    public function __construct()
    {
        parent::__construct(
            "/attributes",
            'attribute'
        );
    }

    public function get_items(WP_REST_Request $request): WP_REST_Response
    {
        $handler = $this->prepare_handler();
        return new WP_REST_Response($handler->get_items());
    }

    public function get_item(WP_REST_Request $request): WP_REST_Response
    {

        $handler = $this->prepare_handler();
        return new WP_REST_Response($handler->get_item((int)$request['id']));
    }

    private function prepare_handler(): PWP_IHandler
    {
        $logger = new PWP_Null_Logger();
        return new PWP_Product_Attribute_Handler($logger);
    }
}
