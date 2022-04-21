<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\authentication\PWP_IApiAuthenticator;


class PWP_Product_Variations_Endpoint extends PWP_EndpointController
{
    public function __construct(string $namespace, PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            $namespace,
            $authenticator,
            '/products/(?P<productId>\d+)/variations',
            'variations'
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
