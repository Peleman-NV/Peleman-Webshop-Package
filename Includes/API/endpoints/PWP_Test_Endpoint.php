<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\API\endpoints\PWP_EndpointController;
use PWP\includes\authentication\PWP_IApiAuthenticator;
use PWP\includes\handlers\PWP_Category_Handler;
use PWP\includes\handlers\services\PWP_Product_Category_SVC;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\PWP_WPDB;

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
