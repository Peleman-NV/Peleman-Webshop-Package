<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\authentication\PWP_IApiAuthenticator;


class PWP_Product_Variations_Endpoint extends PWP_EndpointController
{
    public function __construct(PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            '/products/(?P<productId>\d+)/variations',
            'variations',
            $this->authenticator = $authenticator
        );
    }

    public function register_routes(string $namespace): void
    {
        register_rest_route(
            $namespace,
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
                    'args' => array(),
                ),
                // 'schema' => array($this, 'get_item_array')
            )
        );

        register_rest_route(
            $namespace,
            $this->rest_base . "/(?P<id>\d+)",
            array(
                array(
                    "methods" => \WP_REST_Server::DELETABLE,
                    "callback" => array($this, 'delete_item'),
                    "permission_callback" => array($this, 'auth_delete_item'),
                    'args' => array(),
                ),
                array(
                    "methods" => \WP_REST_Server::READABLE,
                    "callback" => array($this, 'get_item'),
                    "permission_callback" => array($this, 'auth_get_item'),
                    'args' => array(),
                ),
                array(
                    "methods" => \WP_REST_Server::EDITABLE,
                    "callback" => array($this, 'update_item'),
                    "permission_callback" => array($this, 'auth_update_item'),
                    'args' => array(),
                )
            )
        );
    }

    public function create_item(WP_REST_Request $request): WP_REST_Response
    {
        return parent::create_item($request);
    }

    public function get_items(WP_REST_Request $request): WP_REST_Response
    {
        return parent::get_items($request);
    }

    public function get_item(WP_REST_Request $request): WP_REST_Response
    {
        return parent::get_item($request);
    }

    public function update_item(WP_REST_Request $request): WP_REST_Response
    {
        return parent::update_item($request);
    }

    public function delete_item(WP_REST_Request $request): WP_REST_Response
    {
        return parent::delete_item($request);
    }
}
