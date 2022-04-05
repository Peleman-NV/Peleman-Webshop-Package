<?php

declare(strict_types=1);

namespace PPA\includes\authentication;

use WP_REST_Request;

defined('ABSPATH') || die;

class PPA_Authenticator implements PPA_IApiAuthenticator
{
    public function auth_get_item(WP_REST_Request $request): bool
    {
        //TODO: implement proper authentication functionality
        return true;
    }

    public function auth_get_items(WP_REST_Request $request): bool
    {
        return true;
    }

    public function auth_delete_item(WP_REST_Request $request): bool
    {
        return true;
    }

    public function auth_post_item(WP_REST_Request $request): bool
    {
        return true;
    }

    public function auth_update_item(WP_REST_Request $request): bool
    {
        return true;
    }
}
