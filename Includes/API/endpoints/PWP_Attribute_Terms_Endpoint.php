<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\PWP_IApiAuthenticator;

class PWP_Attribute_Terms_Endpoint extends PWP_EndpointController
{
    public function __construct(PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            "/attributes/(?P<attributeId>\d+)/terms',",
            'attribute term',
            $authenticator
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
}
