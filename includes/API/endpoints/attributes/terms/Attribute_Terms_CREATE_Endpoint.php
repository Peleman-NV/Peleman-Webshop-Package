<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\attributes\terms;

use PWP\includes\API\endpoints\Abstract_CREATE_Endpoint;
use PWP\includes\handlers\commands\Create_Term_Command;
use PWP\includes\handlers\services\Term_SVC;
use PWP\includes\utilities\response\Response;
use PWP\includes\wrappers\Term_Data;
use WP_REST_Request;
use WP_REST_Response;

class Attribute_Terms_CREATE_Endpoint extends Abstract_CREATE_Endpoint
{
    private string $prefix = 'pa_';

    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        $slug = $request['slug'];
        $taxonomy = $this->prefix . $slug;

        $response = Response::success('create', 'creating new attribute terms');
        foreach ($request['data'] as $term) {
            $command = new Create_Term_Command(new Term_SVC($taxonomy, 'em', $slug), new Term_Data($term));
            $response->add_response_component($command->do_action());
        }
        return new WP_REST_Response($response->to_array(), 200);
    }
}
