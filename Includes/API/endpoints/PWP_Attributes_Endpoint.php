<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\utilities\PWP_Null_Logger;
use PWP\includes\authentication\PWP_IApiAuthenticator;
use PWP\includes\handlers\PWP_Product_Attribute_Handler;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;

class PWP_Attributes_Endpoint extends PWP_Abstract_READ_Endpoint
{
    public function __construct(PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            "/attributes",
            'attribute',
            $this->authenticator = $authenticator
        );
    }

    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
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

    private function prepare_handler(): PWP_Product_Attribute_Handler
    {
        $logger = new PWP_Null_Logger();
        return new PWP_Product_Attribute_Handler($logger);
    }
}
