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
    public function register_routes(): void
    {
        register_rest_route(
            $this->namespace,
            $this->rest_base,
            array(
                array(
                    "methods" => \WP_REST_Server::READABLE,
                    "callback" => array($this, 'get_items'),
                    "permission_callback" => array($this, 'auth_get_items'),
                    'args' => $this->get_argument_schema()->to_array(),
                ),
                array(
                    "methods" => \WP_REST_Server::CREATABLE,
                    "callback" => array($this, 'create_item'),
                    "permission_callback" => array($this, 'auth_post_item'),
                ),
            )
        );

        register_rest_route(
            $this->namespace,
            $this->rest_base . "/(?P<id>\d+)",
            array(
                array(
                    "methods" => \WP_REST_Server::DELETABLE,
                    "callback" => array($this, 'delete_item'),
                    "permission_callback" => array($this, 'auth_delete_item'),
                ),
                array(
                    "methods" => \WP_REST_Server::READABLE,
                    "callback" => array($this, 'get_item'),
                    "permission_callback" => array($this, 'auth_get_item'),
                ),
            )
        );
    }

    public function get_items(WP_REST_Request $request): object
    {
        $handler = new PWP_Product_Attribute_Handler('');
        return new WP_REST_Response($handler->get_items());
    }

    public function get_item(WP_REST_Request $request): object
    {
        $handler = new PWP_Product_Attribute_Handler('');
        return new WP_REST_Response($handler->get_item((int)$request['id']));
    }
}