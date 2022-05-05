<?php

declare(strict_types=1);

namespace PWP\includes\authentication;

use WP_REST_Request;

defined('ABSPATH') || die;

class PWP_Authenticator implements PWP_IApiAuthenticator
{
    public function auth_get_item(WP_REST_Request $request): bool
    {
        //TODO: implement proper authentication functionality
        // return wc_rest_check_manager_permissions( 'attributes', 'read' );
        return true;
    }

    public function auth_get_items(WP_REST_Request $request): bool
    {
        //TODO: implement proper authentication functionality
        // return wc_rest_check_manager_permissions( 'attributes', 'read' );
        return true;
    }

    public function auth_delete_item(WP_REST_Request $request): bool
    {
        //TODO: implement proper authentication functionality
        // return wc_rest_check_manager_permissions( 'attributes', 'delete' );
        return true;
    }

    public function auth_post_item(WP_REST_Request $request): bool
    {
        //TODO: implement proper authentication functionality
        // return wc_rest_check_manager_permissions( 'attributes', 'create' );
        return true;
    }

    public function auth_update_item(WP_REST_Request $request): bool
    {
        //TODO: implement proper authentication functionality
        // return wc_rest_check_manager_permissions( 'attributes', 'edit' );
        return true;
    }

    public function auth_batch_items(WP_REST_Request $request): bool
    {
        //TODO: implement proper authentication functionality
        // return wc_rest_check_manager_permissions( 'attributes', 'batch' );
        return true;
    }
}
