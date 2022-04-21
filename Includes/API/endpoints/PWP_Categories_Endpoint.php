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
use WP_Term;

class PWP_Categories_Endpoint extends PWP_EndpointController implements PWP_IEndpoint
{

    public function __construct(PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            "/categories",
            'category',
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

        Register_rest_route(
            $namespace,
            $this->rest_base . '/batch',
            array(
                array(
                    'methods' => \WP_REST_Server::EDITABLE,
                    'callback' => array($this, 'batch_items'),
                    "permission_callback" => array($this, 'auth_batch_items'),
                    'args' => array(),
                )
            )
        );
    }

    public function create_item(WP_REST_Request $request): WP_REST_Response
    {
        try {

            $handler = $this->prepare_handler();
            $response = $handler->create_item($request['name'], $request->get_body_params());

            if ($response instanceof WP_Term) {
                return rest_ensure_response($response->data);
            }
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

    public function batch_items(WP_REST_Request $request): WP_REST_Response
    {
        $updates = $request['update'];
        $create = $request['create'];
        $delete = $request['delete'];

        return new WP_REST_Response("we're not quite there yet, but we will be soon!", 501);
    }

    private function prepare_handler(): PWP_IHandler
    {
        $logger = new PWP_Null_Logger();
        return new PWP_Category_Handler($logger);
    }

    function get_argument_schema(): PWP_ISchema
    {
        $factory = new PWP_Schema_Factory(PWP_TEXT_DOMAIN);
        $schema = parent::get_argument_schema();
        $schema
            ->add_property(
                'name',
                $factory->string_property('name of the attribute')
                    ->required()
            )->add_property(
                'slug',
                $factory->string_property("slug of the category. If not given, will create a new slug from the name.")
            )->add_property(
                'description',
                $factory->string_property('description of the category')
            )->add_property(
                'lang',
                $factory->enum_property(
                    'language of the category for use with WPML',
                    array(
                        'en',
                        'es'
                    )
                )
            )->add_property(
                'parent-id',
                $factory->int_property('id of the parent category, if applicable. recommended to not pass this AND the parent-slug parameter at the same time.')
            )->add_property(
                'parent-slug',
                $factory->string_property('slug of the parent category, if applicable. recommended to not pass this AND the parent-id parameter at the same time.')
            )->add_property(
                'display',
                $factory->enum_property(
                    'category archive display type',
                    array(
                        'default',
                        'products',
                        'subcategories',
                        'both'
                    )
                )
                    ->default('default')
            )->add_property(
                'image-id',
                $factory->int_property('id of the category display image')
            );

        return $schema;
    }
}
