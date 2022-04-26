<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_Term;
use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\handlers\PWP_Category_Handler;
use PWP\includes\utilities\schemas\PWP_ISchema;
use PWP\includes\API\endpoints\PWP_EndpointController;
use PWP\includes\authentication\PWP_IApiAuthenticator;
use PWP\includes\utilities\schemas\PWP_Schema_Factory;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;
use PWP\includes\handlers\services\PWP_Product_Category_SVC;

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
            $handler = new PWP_Category_Handler();
            $response = $handler->create_item($request['name'], $request->get_body_params());

            if ($response instanceof WP_Term) {
                return rest_ensure_response($response->data);
            }
        } catch (\Exception $exception) {
            return new WP_REST_Response(array(
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ), $exception->getCode());
        }
    }

    public function get_items(WP_REST_Request $request): WP_REST_Response
    {
        $handler = new PWP_Category_Handler();
        return new WP_REST_Response($handler->get_items($request->get_params()));
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
        $creates = $request['create'];
        $deletes = $request['delete'];

        $notices = array();

        $handler = new PWP_Category_Handler();

        foreach ($updates as $update) {
            try {
                $term = $handler->update_item_by_slug($update['slug'], $update);
                $notices[] = "successfully updated category {$term->slug}";
            } catch (\Exception $exception) {
                $notices[] = "error when updating category {$update['id']}: {$exception->getMessage()}";
            }
        }

        foreach ($creates as $create) {
            $notices[] = $handler->create_item($create['name'], $create);
        }

        foreach ($deletes as $delete) {
            $notices[] = $handler->delete_item_by_slug($delete['slug'], $create);
        }

        return new WP_REST_Response("we're not quite there yet, but we will be soon!", 501);
    }

    function get_argument_schema(): PWP_ISchema
    {
        $factory = new PWP_Schema_Factory(PWP_TEXT_DOMAIN);
        $schema = parent::get_argument_schema();
        $schema
            ->add_property(
                'name',
                $factory->string_property('name of the category')
                    ->required()
            )->add_property(
                'slug',
                $factory->string_property("slug of the category. If not given, will create a new slug from the name. should not contain spaces")
            )->add_property(
                'parent_id',
                $factory->int_property('id of the parent category, if applicable. will precede the parent_slug if present')
            )->add_property(
                'parent_slug',
                $factory->string_property('slug of the parent category, if applicable. will supercede the parent_id if present')
            )->add_property(
                'english_slug',
                $factory->string_property('slug of the default language category, used for uploading translated categories')
            )->add_property(
                'language_code',
                $factory->enum_property(
                    'language code of a translated category. should match the suffix of the slug if present',
                    array(
                        'en',
                        'es'
                    )
                )
            )->add_property(
                'description',
                $factory->string_property('description of the category')
            )->add_property(
                'display',
                $factory->enum_property(
                    'how the category is displayed in the archive',
                    array(
                        'default',
                        'products',
                        'subcategories',
                        'both'
                    )
                )
                    ->default('default')
            )->add_property(
                'image_id',
                $factory->int_property('id of the image to be used with this category in displaying categories')
            )
            ->add_property(
                'seo',
                $factory->array_property('search engine optimization properties')
                    ->add_property(
                        'focus_keyword',
                        $factory->string_property('focus keyword for YOAST SEO')
                    )
                    ->add_property(
                        'description',
                        $factory->string_property('description of the SEO data for YOAST')
                    )
            );


        return $schema;
    }
}
