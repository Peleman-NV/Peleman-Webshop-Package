<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;
use WP_REST_Response;

use PWP\includes\authentication\PWP_I_Api_Authenticator;

defined('ABSPATH') || die;

class PWP_Test_Endpoint extends PWP_Endpoint_Controller
{
    public function __construct(string $namespace, PWP_I_Api_Authenticator $authenticator)
    {
        parent::__construct(
            $namespace,
            '/test',
            'test',
            $this->authenticator = $authenticator
        );
    }

    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        return new WP_REST_Response('test successful!', 200);
        // return new WP_REST_Response($results, 200);
    }

    public function authenticate(WP_REST_Request $request): bool
    {
        return true;
    }

    public function get_arguments(): array
    {
        return [];
    }

    public function get_methods(): string
    {
        return \WP_REST_Server::ALLMETHODS;
    }
}
