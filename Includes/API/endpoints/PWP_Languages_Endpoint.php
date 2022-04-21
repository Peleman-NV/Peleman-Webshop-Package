<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\PWP_IApiAuthenticator;
use WP_REST_Request;
use WP_REST_Response;

class PWP_Languages_Endpoint extends PWP_EndpointController
{
    public function __construct()
    {
        parent::__construct(
            '/languages',
            'languages'
        );
    }

    public function get_items(WP_REST_Request $request): WP_REST_Response
    {
        if (has_filter('wpml_active_languages')) {
            $languages = apply_filters('wpml_active_languages', null, 'orderby=id&order=desc');
            return new WP_REST_Response((array)$languages);
        }

        return new WP_REST_Response('WPML is not active or the required filter has not been registered!', 500);
    }
}
