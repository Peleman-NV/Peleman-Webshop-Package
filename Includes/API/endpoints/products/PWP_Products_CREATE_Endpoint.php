<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\products;

use PWP\includes\API\endpoints\PWP_Abstract_CREATE_Endpoint;
use PWP\includes\API\PWP_Channel_Definition;
use PWP\includes\authentication\PWP_I_Api_Authenticator;
use PWP\includes\editor\PWP_Product_IMAXEL_Data;
use PWP\includes\editor\PWP_Product_Meta;
use PWP\includes\editor\PWP_Product_Meta_Data;
use PWP\includes\editor\PWP_Product_PIE_Data;
use PWP\includes\handlers\commands\PWP_Create_Simple_Product_Command;
use PWP\includes\utilities\PWP_SitePress_Wrapper;
use PWP\includes\utilities\PWP_WPDB;
use PWP\includes\utilities\response\PWP_Error_Response;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\utilities\schemas\PWP_Resource_Schema;
use WC_Product;
use WC_Product_Simple;
use WP_REST_Request;
use WP_REST_Response;

class PWP_Products_CREATE_Endpoint extends PWP_Abstract_CREATE_Endpoint
{
    public function __construct(PWP_Channel_Definition $channel, PWP_I_Api_Authenticator $authenticator)
    {
        parent::__construct(
            $channel->get_namespace(),
            $channel->get_route(),
            $channel->get_title(),
            $authenticator
        );
    }

    public function do_action(WP_REST_Request $request): WP_REST_Response
    {


        $response = new PWP_Response('request not processed', false, 500);

        switch ($request['type']) {
            case 'variable':
                break;
            case 'variant':
                break;
            default:
            case 'simple':
                $response = $this->create_new_simple_product($request);
                break;
        }
        return new WP_REST_Response(
            $response->to_array(),
            200
        );
    }

    private function create_new_simple_product(WP_REST_Request $request): PWP_I_Response
    {
        $requestData = $this->validate_request_with_schema($request->get_json_params());
        $command = new PWP_Create_Simple_Product_Command($requestData);
        return $command->do_action();
    }

    public function get_arguments(): array
    {
        return array(
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'title' => 'Product',
            'type' => 'object',
            'properties' => array(
                'name' => array(
                    'type' => 'string',
                    'context' => array('view', 'edit'),
                    'required' => true,
                ),
                'sku' => array(
                    'type' => 'string',
                    'context' => array('view', 'edit'),
                    'required' => true,
                )
            ),
            'required' => array(
                'name',
                'sku'
            )
        );
    }
}
