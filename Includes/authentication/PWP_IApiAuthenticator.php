<?php

declare(strict_types=1);

namespace PWP\includes\authentication;

use WP_REST_Request;

interface PWP_IApiAuthenticator
{
    public function auth_get_item(WP_REST_Request $request): bool;

    public function auth_get_items(WP_REST_Request $request): bool;

    public function auth_post_item(WP_REST_Request $request): bool;

    public function auth_update_item(WP_REST_Request $request): bool;

    public function auth_delete_item(WP_REST_Request $request): bool;
}