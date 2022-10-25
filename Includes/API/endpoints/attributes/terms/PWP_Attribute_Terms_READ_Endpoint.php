<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\attributes\terms;

use PWP\includes\API\endpoints\PWP_Abstract_READ_Endpoint;
use WP_REST_Request;
use WP_REST_Response;

class PWP_Attribute_Terms_READ_Endpoint extends PWP_Abstract_READ_Endpoint
{
    private string $prefix = 'pa_';

    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        $slug = $request['slug'];

        //check if taxonomy 
        // if (!isset($attributes[$slug])) {
        //     return new WP_REST_Response(array(
        //         'status' => 'failure',
        //         'message' => 'attribute not found in active list of attribute taxonomies!'
        //     ), 404);
        // }

        $terms = get_terms(array(
            'taxonomy' => $this->prefix . $slug,
            'hide_empty' => false,
            // easy way to find a specific term by slug. should help in the future.
            // 'slug' => 'standard',
        ));

        if (is_wp_error($terms)) {
            return new WP_REST_Response(
                array(
                    'status' => 'failure',
                    'message' => "no attribute found with slug {$slug}."
                ),
                404
            );
        }

        return new WP_REST_Response($terms, 200);
    }
}
