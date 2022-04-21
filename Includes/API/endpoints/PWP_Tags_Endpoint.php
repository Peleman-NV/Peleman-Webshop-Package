<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\API\PWP_API_Logger;
use PWP\includes\handlers\PWP_Tag_Handler;
use PWP\includes\utilities\PWP_ArgBuilder;
use PWP\includes\utilities\PWP_Null_Logger;
use PWP\includes\utilities\schemas\PWP_ISchema;
use PWP\includes\authentication\PWP_IApiAuthenticator;
use PWP\includes\utilities\schemas\PWP_Schema_Factory;
use PWP\includes\utilities\schemas\PWP_Argument_Schema;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;

class PWP_Tags_Endpoint extends PWP_EndpointController
{
    public function __construct(PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            "/tags",
            'tag',
            $this->authenticator = $authenticator
        );
    }

    public function register_routes(string $namespace): void
    {
        register_rest_route(
            $namespace,
            $this->rest_base,
            array(
                array(
                    "methods" => \WP_REST_Server::READABLE,
                    "callback" => array($this, 'get_items'),
                    "permission_callback" => array($this, 'auth_get_items'),
                    'args' => $this->get_argument_schema()->to_array(),
                ),
                array(
                    "methods" => \WP_REST_Server::CREATABLE,
                    "callback" => array($this, 'create_item'),
                    "permission_callback" => array($this, 'auth_post_item'),
                    'args' => array(),
                ),
                // 'schema' => array($this, 'get_item_array')
            )
        );

        register_rest_route(
            $namespace,
            $this->rest_base . "/(?P<id>\d+)",
            array(
                array(
                    "methods" => \WP_REST_Server::DELETABLE,
                    "callback" => array($this, 'delete_item'),
                    "permission_callback" => array($this, 'auth_delete_item'),
                    'args' => array(),
                ),
                array(
                    "methods" => \WP_REST_Server::READABLE,
                    "callback" => array($this, 'get_item'),
                    "permission_callback" => array($this, 'auth_get_item'),
                    'args' => array(),
                ),
                array(
                    "methods" => \WP_REST_Server::EDITABLE,
                    "callback" => array($this, 'update_item'),
                    "permission_callback" => array($this, 'auth_update_item'),
                    'args' => array(),
                )
            )
        );
    }

    public function create_item(WP_REST_Request $request): WP_REST_Response
    {
        try {
            $args = $request->get_body_params();

            $logger = (bool)$request['logs'] ? new PWP_API_Logger() :  new PWP_Null_Logger();
            $handler = new PWP_Tag_Handler($logger);
            $response = $handler->create_item($request['name'], $args);

            return rest_ensure_response($handler->get_item($response['term_id']));
        } catch (\Exception $exception) {
            return new WP_REST_Response($exception->getMessage(), $exception->getCode());
        }
    }

    public function get_items(WP_REST_Request $request): WP_REST_Response
    {
        try {
            $logger = (bool)$request['logs'] ? new PWP_API_Logger() :  new PWP_Null_Logger();
            $handler = new PWP_Tag_Handler($logger);
            // $args = new PWP_ArgBuilder();
            // $args
            //     ->add_arg_from_request($request, 'hide_empty', false)
            //     ->add_arg_from_request($request, 'number', 0)
            //     ->add_arg_from_request($request, 'offset', 0)
            //     ->add_arg_from_request($request, 'count', false)
            //     ->add_arg_from_request($request, 'slug');

            $data = $handler->get_items($request->get_body_params());
        } catch (\Exception $exception) {
            $data = new WP_REST_Response($exception->getMessage(), $exception->getCode());
        }

        return new WP_REST_Response($data);
    }

    public function get_item(WP_REST_Request $request): WP_REST_Response
    {
        $id = (int)$request['id'];
        $logger = (bool)$request['logs'] ? new PWP_API_Logger() :  new PWP_Null_Logger();
        $handler = new PWP_Tag_Handler($logger);
        $data = $handler->get_item($id);
        return new WP_REST_RESPONSE($data);
    }

    public function update_item(WP_REST_Request $request): WP_REST_Response
    {
        throw new PWP_Not_Implemented_Exception();
    }

    public function delete_item(WP_REST_Request $request): WP_REST_Response
    {
        $logger = (bool)$request['logs'] ? new PWP_API_Logger() :  new PWP_Null_Logger();
        $handler = new PWP_Tag_Handler($logger);
        $outcome = $handler->delete_item($request['id']);

        return new WP_REST_Response($outcome ? 'tag successfully deleted' : 'tag not deleted for unknown reasons', 200);
    }

    public function get_argument_schema(): PWP_ISchema
    {
        $factory = new PWP_Schema_Factory('default');
        $schema = new PWP_Argument_Schema();

        $schema
            ->add_property(
                'name',
                $factory->string_property('name of the tag')
                    ->required()
            )->add_property(
                'slug',
                $factory->string_property('slug of the tag. if this argument is not passed, the slug will be generated from the name.')
            )->add_property(
                'description',
                $factory->string_property("HTML description of the resource")
            );

        return $schema;
    }

    public function get_item_schema(): PWP_ISchema
    {
        $schema = parent::get_item_schema();
        return $schema;
    }
}
