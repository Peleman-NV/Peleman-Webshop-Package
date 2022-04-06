<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;

interface PWP_IEndpoint
{
    public function register(): void;

    public function get_item(WP_REST_Request $request): object;
    public function get_items(WP_REST_Request $request): object;

    public function update_item(WP_REST_Request $request): object;

    public function delete_item(WP_REST_Request $request): object;
    
    public function post_item(WP_REST_Request $request): object;
}
