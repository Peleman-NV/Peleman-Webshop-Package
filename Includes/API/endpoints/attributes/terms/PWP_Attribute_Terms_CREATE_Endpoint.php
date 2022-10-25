<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\attributes\terms;

use PWP\includes\API\endpoints\PWP_Abstract_CREATE_Endpoint;
use PWP\includes\handlers\commands\PWP_Create_Term_Command;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\wrappers\PWP_Term_Data;
use WP_REST_Request;
use WP_REST_Response;

class PWP_Attribute_Terms_CREATE_Endpoint extends PWP_Abstract_CREATE_Endpoint
{
    private string $prefix = 'pa_';

    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        $slug = $request['slug'];
        $taxonomy = $this->prefix . $slug;

        $response = PWP_Response::success('create', 'creating new attribute terms');
        foreach ($request['data'] as $term) {
            $command = new PWP_Create_Term_Command(new PWP_Term_SVC($taxonomy, 'em', $slug), new PWP_Term_Data($term));
            $response->add_response_component($command->do_action());
        }
        return new WP_REST_Response($response->to_array(), 200);
    }
}
