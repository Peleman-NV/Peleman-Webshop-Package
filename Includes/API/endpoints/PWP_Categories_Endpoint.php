<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\handlers\PWP_IHandler;
use PWP\includes\utilities\PWP_ArgBuilder;
use PWP\includes\utilities\PWP_Null_Logger;
use PWP\includes\handlers\PWP_Category_Handler;
use PWP\includes\utilities\schemas\PWP_ISchema;
use PWP\includes\API\endpoints\PWP_EndpointController;
use PWP\includes\authentication\PWP_IApiAuthenticator;
use PWP\includes\utilities\schemas\PWP_Schema_Factory;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;

class PWP_Categories_Endpoint extends PWP_EndpointController implements PWP_IEndpoint
{

    public function __construct()
    {
        parent::__construct(
            "/categories",
            'category'
        );
    }

    public function create_item(WP_REST_Request $request): WP_REST_Response
    {
        try {
            $args = new PWP_ArgBuilder();
            $args
                ->add_arg_from_request($request, 'slug')
                ->add_arg_from_request($request, 'description');

            $handler = $this->prepare_handler();
            $response = $handler->create_item($request['name'], $args->to_array());

            return rest_ensure_response($handler->get_item($response['term_id']));
        } catch (\Exception $exception) {
            return new WP_REST_Response($exception->getMessage(), $exception->getCode());
        }
    }

    public function get_items(WP_REST_Request $request): WP_REST_Response
    {
        $handler = $this->prepare_handler();
        return new WP_REST_Response($handler->get_items());
    }

    public function get_item(WP_REST_Request $request): WP_REST_Response
    {
        throw new PWP_Not_Implemented_Exception();
    }

    public function update_item(WP_REST_Request $request): WP_REST_Response
    {
        throw new PWP_Not_Implemented_Exception();
    }

    public function delete_item(WP_REST_Request $request): WP_REST_Response
    {
        throw new PWP_Not_Implemented_Exception();
    }

    private function prepare_handler(): PWP_IHandler
    {
        $logger = new PWP_Null_Logger();
        return new PWP_Category_Handler($logger);
    }

    function get_argument_schema(): PWP_ISchema
    {
        $factory = new PWP_Schema_Factory('default');
        $schema = parent::get_argument_schema();
        $schema
            ->add_property(
                'name',
                $factory->string_property('name of the attribute')
                    ->required()
            )->add_property(
                'slug',
                $factory->string_property("slug of the category. If not given, will create a new slug from the name.")
            )
            ->add_property(
                'description',
                $factory->string_property('description of the category')
            )
            ->add_property(
                'lang',
                $factory->enum_property('language of the category for use with WPML', array('en', 'es'))
            )
            ->add_property(
                'parent-id',
                $factory->int_property('id of the parent category, if applicable.')
            );

        return $schema;
    }
}
