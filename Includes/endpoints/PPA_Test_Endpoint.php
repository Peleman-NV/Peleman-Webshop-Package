<?php

declare(strict_types=1);

namespace PPA\includes\endpoints;

use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use PPA\includes\authentication\PPA_Authenticator;
use PPA\includes\endpoints\PPA_EndpointController;

defined('ABSPATH') || die;

class PPA_Test_Endpoint extends PPA_EndpointController
{
    public function __construct(string $namespace, PPA_Authenticator $authenticator)
    {
        parent::__construct(
            $namespace,
            '/test',
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
                    'methods' => WP_REST_Server::ALLMETHODS,
                    'callback' => array($this, 'get_item'),
                    'permission_callback' => array($this, 'authenticate'),
                ),
            ),
        );
    }

    public function get_item(WP_REST_Request $request): object
    {
        return new WP_REST_Response('test successful!');
    }
}
