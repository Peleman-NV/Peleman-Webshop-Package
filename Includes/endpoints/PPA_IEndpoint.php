<?php

declare(strict_types=1);

namespace PPA\includes\endpoints;

use WP_REST_Request;

interface PPA_IEndpoint
{
    public function register(): void;

    public function get_callback(WP_REST_Request $request): object;
    public function update_callback(WP_REST_Request $request): object;
    public function delete_callback(WP_REST_Request $request): object;
    public function post_callback(WP_REST_Request $request): object;
}
