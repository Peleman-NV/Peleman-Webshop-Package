<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\PWP_IApiAuthenticator;
use WP_REST_Request;
use WP_REST_Response;

interface PWP_IEndpoint
{
    public function register_routes(string $namespace, PWP_IApiAuthenticator $authenticator): void;

    public function get_item(WP_REST_Request $request): WP_REST_Response;
    public function get_items(WP_REST_Request $request): WP_REST_Response;

    public function update_item(WP_REST_Request $request): WP_REST_Response;

    public function delete_item(WP_REST_Request $request): WP_REST_Response;
    
    public function create_item(WP_REST_Request $request): WP_REST_Response;
}
