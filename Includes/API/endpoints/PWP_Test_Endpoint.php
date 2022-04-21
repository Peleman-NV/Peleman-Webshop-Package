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
    public function __construct()
    {
        parent::__construct(
            '/test',
            'test'
        );
    }

    public function get_item(WP_REST_Request $request): WP_REST_Response
    {
        return new WP_REST_Response('test successful!');
    }

    public function get_items(WP_REST_Request $request): WP_REST_Response
    {
        return $this->get_item($request);
    }

    public function create_item(WP_REST_Request $request): WP_REST_Response
    {
        return $this->get_item($request);
    }

    public function delete_item(WP_REST_Request $request): WP_REST_Response
    {
        return $this->get_item($request);
    }

    public function update_item(WP_REST_Request $request): WP_REST_Response
    {
        return $this->get_item($request);
    }

    public function auth_get_item(WP_REST_Request $request): bool
    {
        return true;
    }
}
