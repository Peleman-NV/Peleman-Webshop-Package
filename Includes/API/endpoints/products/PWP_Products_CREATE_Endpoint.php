<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\products;

use Exception;
use PWP\includes\API\endpoints\PWP_Abstract_CREATE_Endpoint;
use PWP\includes\API\PWP_Channel_Definition;
use PWP\includes\authentication\PWP_I_Api_Authenticator;
use PWP\includes\exceptions\PWP_Invalid_Input_Exception;
use PWP\includes\handlers\commands\PWP_Create_Simple_Product_Command;
use PWP\includes\utilities\response\PWP_Error_Response;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Response;
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

        try {
            $requestData = $this->validate_request_with_schema($request->get_json_params(), 'product');
            switch ($request['type']) {
                case 'variable':
                    break;
                case 'variant':
                    break;
                default:
                case 'simple':
                    $response = $this->create_new_simple_product($requestData);
                    break;
            }
        } catch (PWP_Invalid_Input_Exception $exception) {
            $response = new PWP_Error_Response($exception->getMessage(), 400);
        } catch (Exception $exception) {
            error_log("error happened");
            $response = new PWP_Error_Response("Internal Server Error.", 500);
        } finally {
            return new WP_REST_Response(
                $response->to_array(),
                $response->get_code(),
            );
        }
    }

    private function create_new_simple_product(array $request): PWP_I_Response
    {
        $command = new PWP_Create_Simple_Product_Command($request);
        return $command->do_action();
    }

    public function get_arguments(): array
    {
        $schema = new PWP_Product_Schema();
        return $schema->to_array();
    }
}
