<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use Exception;
use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\handlers\PWP_Tag_Handler;
use PWP\includes\authentication\PWP_IApiAuthenticator;
use PWP\includes\utilities\PWP_ArgBuilder;
use PWP\includes\utilities\schemas\PWP_Argument_Schema;
use PWP\includes\utilities\schemas\PWP_ISchema;
use PWP\includes\utilities\schemas\PWP_Schema_Factory;
use WP_Term;

class PWP_Tags_Endpoint extends PWP_EndpointController
{
    public function __construct(string $namespace, PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            $namespace,
            $authenticator,
            "/tags",
            'tag'
        );
    }
    public function register_routes(): void
    {
        register_rest_route(
            $this->namespace,
            $this->rest_base,
            array(
                array(
                    "methods" => \WP_REST_Server::READABLE,
                    "callback" => array($this, 'get_items'),
                    "permission_callback" => array($this, 'auth_get_items'),
                    // 'args' => $this->get_argument_schema()->to_array(),
                ),
                array(
                    "methods" => \WP_REST_Server::CREATABLE,
                    "callback" => array($this, 'create_item'),
                    "permission_callback" => array($this, 'auth_post_item'),
                ),
            )
        );

        register_rest_route(
            $this->namespace,
            $this->rest_base . "/(?P<id>\d+)",
            array(
                array(
                    "methods" => \WP_REST_Server::DELETABLE,
                    "callback" => array($this, 'delete_item'),
                    "permission_callback" => array($this, 'auth_delete_item'),
                ),
                array(
                    "methods" => \WP_REST_Server::READABLE,
                    "callback" => array($this, 'get_item'),
                    "permission_callback" => array($this, 'auth_get_item'),
                ),
            )
        );
    }

    public function get_items(WP_REST_Request $request): object
    {
        try {
            $handler = new PWP_Tag_Handler();
            $args = new PWP_ArgBuilder();
            $args
                ->add_arg_from_request($request, 'hide_empty', false)
                ->add_arg_from_request($request, 'number', 0)
                ->add_arg_from_request($request, 'offset', 0)
                ->add_arg_from_request($request, 'count', false)
                ->add_arg_from_request($request, 'slug');

            $data = $handler->get_items($args->to_array());
        } catch (\Exception $exception) {
            $data = new WP_REST_Response($exception->getMessage(), $exception->getCode());
        }

        return new WP_REST_Response($data);
    }

    public function get_item(WP_REST_Request $request): object
    {
        $id = (int)$request['id'];
        $handler = new PWP_Tag_Handler();
        $data = $handler->get_item($id);
        return new WP_REST_RESPONSE($data);
    }

    public function create_item(WP_REST_Request $request): object
    {
        try {
            $args = new PWP_ArgBuilder();
            $args
                ->add_required_arg_from_request($request, 'name')
                ->add_arg_from_request($request, 'slug')
                ->add_arg_from_request($request, 'description');

            $handler = new PWP_Tag_Handler();
            $response = $handler->create_item($args->to_array());

            return rest_ensure_response($handler->get_item($response['term_id']));
        } catch (\Exception $exception) {
            return new WP_REST_Response($exception->getMessage(), $exception->getCode());
        }
    }

    public function delete_item(WP_REST_Request $request): object
    {
        $handler = new PWP_Tag_Handler();
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
}
