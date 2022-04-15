<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\API\endpoints\PWP_EndpointController;
use PWP\includes\authentication\PWP_IApiAuthenticator;

defined('ABSPATH') || die;

class PWP_Test_Endpoint extends PWP_EndpointController
{
    public function __construct(string $namespace, PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            $namespace,
            $authenticator,
            '/test',
            'test'
        );
    }

    public function register(): void
    {
        register_rest_route(
            $this->namespace,
            $this->rest_base,
            array(
                array(
                    'methods' => WP_REST_Server::ALLMETHODS,
                    'callback' => array($this, 'get_item'),
                    'permission_callback' => array($this, 'auth_get_item'),
                ),
            ),
        );
    }
    
    public function get_item(WP_REST_Request $request): object
    {
        return new WP_REST_Response('test successful!');
    }

    public function auth_get_item(WP_REST_Request $request): bool
    {
        return true;
    }
}
