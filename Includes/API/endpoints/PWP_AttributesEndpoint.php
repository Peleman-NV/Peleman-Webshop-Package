<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\authentication\PWP_IApiAuthenticator;

class PWP_AttributesEndpoint extends PWP_EndpointController
{

    
    public function __construct(string $namespace, PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            $namespace,
            "/attributes",
            $authenticator
        );
    }
    public function register(): void
    {
        register_rest_route(
            $this->namespace,
            $this->rest_base,
            array(
                array(
                    "methods" => \WP_REST_Server::READABLE,
                    "callback" => array($this, 'get_items'),
                    "permission_callback" => array($this, 'auth_get_items'),
                    'args' => $this->get_params_schema(),
                ),
                array(
                    "methods" => \WP_REST_Server::CREATABLE,
                    "callback" => array($this, 'create_item'),
                    "permission_callback" => array($this, 'auth_post_item'),
                ),
            )
        );
    }

    public function get_item(WP_REST_Request $request): object
    {
        return new WP_REST_Response();
    }
}