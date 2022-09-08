<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\attributes;

use PWP\includes\API\endpoints\PWP_Abstract_CREATE_Endpoint;
use PWP\includes\API\PWP_Channel_Definition;
use PWP\includes\authentication\PWP_I_Api_Authenticator;
use PWP\includes\handlers\commands\PWP_Create_Product_Attribute_Command;
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
        $name = $request['name'];
        $slug = $request['slug'];
        $type = $request['type'] ?: 'select';
        $orderBy = $request['order_by'] ?: 'menu_order';
        $hasArchives = ($request['has_archives']) ?: false;

        $createCommand = new PWP_Create_Product_Attribute_Command(
            $name,
            $slug,
            $type,
            $orderBy,
            $hasArchives
        );

        $response = $createCommand->do_action();

        if($response->)
        //create terms within taxonomy
        foreach ($request['terms'] as $term) {
            if (term_exists($term['slug'], $taxonomy)) {
                $name_id = get_term_by('name', $name, $taxonomy, ARRAY_A)['term_id'];
            } else {
                $name_data = wp_insert_term($name, $taxonomy, array(
                    'description' => $term['description'] ?: '',
                    'slug' => $term['slug'],
                    'menu_order' => $term['menu_order'],
                ));
                $name_id = $name_data['term_id'];
            }
        }



        return new WP_REST_Response('test - create attributes endpoint');
    }
}
