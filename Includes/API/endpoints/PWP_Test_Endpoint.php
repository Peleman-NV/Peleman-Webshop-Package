<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\API\endpoints\PWP_EndpointController;
use PWP\includes\authentication\PWP_IApiAuthenticator;

defined('ABSPATH') || die;

class PWP_Test_Endpoint extends PWP_EndpointController
{
    public function __construct(PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            '/test',
            'test',
            $this->authenticator = $authenticator
        );
    }

    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        return new WP_REST_Response('test successful!', 200);
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
