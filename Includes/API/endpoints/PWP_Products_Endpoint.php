<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\utilities\schemas\PWP_Schema_Factory;
use PWP\includes\API\endpoints\PWP_EndpointController;
use PWP\includes\authentication\PWP_IApiAuthenticator;
use PWP\includes\handlers\PWP_Product_Handler;
use PWP\includes\utilities\schemas\PWP_Argument_Schema;
use PWP\includes\utilities\schemas\PWP_ISchema;
use WP_REST_Request;
use WP_REST_Response;

class PWP_Products_Endpoint extends PWP_EndpointController implements PWP_IEndpoint
{
    private const PAGE_SOFT_CAP = 100;
    //variable for caching a schema. 
    // TODO: look into how we can do this
    private array $schema;

    public function __construct(string $namespace, PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            $namespace,
            $authenticator,
            "/products",
            'product'
        );
    }    

    public function create_item(WP_REST_Request $request): WP_REST_Response
    {
        $handler = new PWP_Product_Handler();

        try {
            $result = $handler->create_item($request['name'], $request->get_body_params());
        } catch (\Exception $exception) {
            return new WP_REST_Response($exception->getMessage(), $exception->getCode());
        }

        return new WP_REST_Response(array(
            'good job! here are your args:',
            $request->get_body_params(),
            $result->get_data(),
        ));
    }

    public function get_item(WP_REST_Request $request): WP_REST_Response
    {
        $handler = new PWP_Product_Handler();
        $product = $handler->get_item($request['id'], $request->get_url_params());

        return new \WP_REST_Response($product->get_data());
    }

    public function get_items(WP_REST_Request $request): WP_REST_Response
    {
        $handler = new PWP_Product_Handler();
        $args = $request->get_url_params();

        $results = $handler->get_items($args);

        return new \WP_REST_Response(
            $results
        );
    }

    public function update_item(WP_REST_Request $request): WP_REST_Response
    {
        return parent::update_item($request);
    }

    public function delete_item(WP_REST_Request $request): WP_REST_Response
    {
        $handler = new PWP_Product_Handler();
        $handler->delete_item($request['id'], $request->get_body_params());

        return new WP_REST_Response(array(
            'message' => isset($request->get_body_params()['force']) ?
                'product permanently deleted successfullly!' :
                'product moved to trash successfully!',
        ));
    }

    public function get_argument_schema(): PWP_ISchema
    {
        $factory = new PWP_Schema_Factory('default');
        $schema = new PWP_Argument_Schema();
        $schema
            ->add_property(
                'SKU',
                $factory->string_property('filter results to matching SKUs. supports partial matches.')
                    ->view()
            )->add_property(
                'limit',
                $factory->int_property('maximum amount of results per call')
                    ->default(self::PAGE_SOFT_CAP)
            )->add_property(
                'page',
                $factory->int_property('when using pageination, represents the page of results to retrieve.')
                    ->default(1)
                    ->add_custom_arg('min', -1)
                    ->add_custom_arg('sanitize_callback', 'absint')
            )->add_property(
                'order',
                $factory->enum_property('how to order; ascending or descending', array(
                    'ASC',
                    'DESC'
                ))->default('ASC')
            )->add_property(
                'type',
                $factory->enum_property('types to match', array_keys(wc_get_product_types()))
            )->add_property(
                'orderby',
                $factory->enum_property('by which parameter to order the resulting output', array(
                    'none',
                    'id',
                    'name',
                    'type',
                    'rand',
                    'date',
                    'modified',
                ))->default('id')
            )->add_property(
                'tag',
                $factory->array_property('limit results to specific tags by slug')
            )->add_property(
                'category',
                $factory->array_property('limit results to specific categories by slug')
            )->add_property(
                'status',
                $factory->multi_enum_property('status to match', array(
                    'draft',
                    'pending',
                    'private',
                    'published',
                    'trash',
                ))
            )->add_property(
                'price',
                $factory->string_property('exact price to match')
            );

        return $schema;
    }

    protected function get_item_schema(): PWP_ISchema
    {
        $schema = parent::get_item_schema();
        return $schema;
    }
}
