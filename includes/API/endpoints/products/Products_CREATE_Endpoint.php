<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\products;

use PWP\includes\API\endpoints\Abstract_CREATE_Endpoint;
use PWP\includes\API\Channel_Definition;
use PWP\includes\authentication\I_Api_Authenticator;
use PWP\includes\exceptions\Invalid_Input_Exception;
use PWP\includes\handlers\commands\Create_Simple_Product_Command;
use PWP\includes\handlers\commands\Create_Variable_Product_Command;
use PWP\includes\handlers\commands\Create_Variation_Product_Command;
use PWP\includes\utilities\notification\I_Notice;
use PWP\includes\utilities\response\Error_Response;
use PWP\includes\utilities\response\Response;
use WP_REST_Request;
use WP_REST_Response;

class Products_CREATE_Endpoint extends Abstract_CREATE_Endpoint
{
    public function __construct(Channel_Definition $channel, I_Api_Authenticator $authenticator)
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
        $response = Response::failure('failure', 'request not processed', 500);

        try {
            $requestData = $this->validate_request_with_schema($request->get_json_params(), 'product');
            switch ($request['product_type']) {
                default:
                case 'simple':
                    $response = $this->create_new_simple_product($requestData);
                    break;
                case 'variable':
                    $response = $this->create_new_variable_product($requestData);
                    break;
                case 'variant':
                    $response = $this->create_new_variant_product($requestData);
                    break;
            }
        } catch (Invalid_Input_Exception $exception) {
            error_log((string)$exception);
            $response = new Error_Response($exception->getMessage(), 400);
        } catch (\Throwable $exception) {
            error_log((string)$exception);
            $response = new Error_Response("Internal Server Error.", 500);
        } finally {
            return new WP_REST_Response(
                $response->to_array(),
                $response->get_code(),
            );
        }
    }

    private function create_new_simple_product(array $request): I_Notice
    {
        $command = new Create_Simple_Product_Command($request);
        return $command->do_action();
    }

    private function create_new_variable_product(array $request): I_Notice
    {
        $command = new Create_Variable_Product_Command($request);
        return $command->do_action();
    }

    private function create_new_variant_product(array $request): I_Notice
    {
        $parentId = wc_get_product_id_by_sku($request['sku']);
        if (empty($parentId)) {
            throw new Invalid_Input_Exception("variable product with sku {$request['sku']} not found");
        }

        $parent = wc_get_product($parentId);
        $command = new Create_Variation_Product_Command($parent, $request);
        return $command->do_action();
    }

    public function get_arguments(): array
    {
        return $this->get_schema()['properties'];
    }

    public function get_schema(): array
    {
        $schema = new Product_Schema();
        return $schema->to_array();
    }
}
