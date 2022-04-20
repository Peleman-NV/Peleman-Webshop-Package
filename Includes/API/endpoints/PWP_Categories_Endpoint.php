<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\API\endpoints\PWP_EndpointController;
use PWP\includes\authentication\PWP_IApiAuthenticator;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;
use PWP\includes\handlers\PWP_Category_Handler;
use PWP\includes\utilities\PWP_ArgBuilder;
use WP_REST_Request;
use WP_REST_Response;

class PWP_Categories_Endpoint extends PWP_EndpointController implements PWP_IEndpoint
{

    public function __construct(string $namespace, PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            $namespace,
            $authenticator,
            "/categories",
            'category'
        );
    }

    public function create_item(WP_REST_Request $request): object
    {
        try {
            $args = new PWP_ArgBuilder();
            $args
                ->add_arg_from_request($request, 'slug')
                ->add_arg_from_request($request, 'description');

            $handler = new PWP_Category_Handler();
            $response = $handler->create_item($request['name'], $args->to_array());

            return rest_ensure_response($handler->get_item($response['term_id']));
        } catch (\Exception $exception) {
            return new WP_REST_Response($exception->getMessage(), $exception->getCode());
        }
    }

    public function get_items(WP_REST_Request $request): object
    {
        $handler = new PWP_Category_Handler();
        return new WP_REST_Response($handler->get_items());
    }

    public function get_item(WP_REST_Request $request): object
    {
        return new PWP_Not_Implemented_Exception();
    }

    public function update_item(WP_REST_Request $request): object
    {
        return new PWP_Not_Implemented_Exception();
    }

    public function delete_item(WP_REST_Request $request): object
    {
        return new PWP_Not_Implemented_Exception();
    }
}
