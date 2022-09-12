<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\attributes;

use PWP\includes\API\endpoints\PWP_Abstract_CREATE_Endpoint;
use PWP\includes\API\PWP_Channel_Definition;
use PWP\includes\authentication\PWP_I_Api_Authenticator;
use PWP\includes\handlers\commands\PWP_Create_Product_Attribute_Command;
use PWP\includes\utilities\response\PWP_Response;
use WP_REST_Request;
use WP_REST_Response;

class PWP_Attributes_Create_Endpoint extends PWP_Abstract_CREATE_Endpoint
{
    public function __construct(PWP_Channel_Definition $channel, PWP_I_Api_Authenticator $authenticator)
    {
        parent::__construct(
            $channel->get_namespace(),
            $channel->get_route(),
            $channel->get_title(),
            $this->authenticator = $authenticator
        );
    }

    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        $response = new PWP_Response('create', true);
        $name = $request['name'];
        $slug = $request['slug'];
        $type = $request['type'] ?: 'select';
        $orderBy = $request['order_by'] ?: 'menu_order';
        $hasArchives = ($request['has_archives']) ?: false;

        if (empty($name) || empty($slug)) return new WP_REST_Response('name or slug missing', 400);

        $createCommand = new PWP_Create_Product_Attribute_Command(
            $name,
            $slug,
            $type,
            $orderBy,
            $hasArchives
        );

        $result = $createCommand->do_action();
        $response = $result;

        // $taxonomy = $createCommand->get_taxonomy();
        // //create terms within taxonomy
        // foreach ($request['terms'] as $term) {
        //     $command = new PWP_Create_Product_Attribute_term_Command(
        //         $request['name'],
        //         $request['slug'],
        //         $taxonomy,
        //         $request['description'],
        //         $request['menuOrder'] ?: 0
        //     );
        //     $response->add_response($command->do_action());
        // }

        return new WP_REST_Response($response->to_array());
    }
}
