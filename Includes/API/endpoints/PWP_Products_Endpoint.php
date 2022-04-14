<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\PWP_ArgBuilder;
use PWP\includes\utilities\PWP_Json_Schema;
use PWP\includes\utilities\PWP_Schema_Factory;
use PWP\includes\API\endpoints\PWP_EndpointController;
use PWP\includes\authentication\PWP_IApiAuthenticator;

class PWP_Products_Endpoint extends PWP_EndpointController implements PWP_IEndpoint
{
    private const PAGE_SOFT_CAP = 10;

    public function __construct(string $namespace, PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            $namespace,
            "/products",
            $authenticator
        );
    }

    public function register(): void
    {
        register_rest_route(
            $this->namespace,
            $this->rest_base,
            array(
                array(
                    "methods" => \WP_REST_Server::READABLE,
                    "callback" => array($this, 'get_items'),
                    "permission_callback" => array($this, 'auth_get_items'),
                    'args' => array(),
                ),
                array(
                    "methods" => \WP_REST_Server::CREATABLE,
                    "callback" => array($this, 'create_item'),
                    "permission_callback" => array($this, 'auth_post_item'),
                    'args' => array(),
                ),
                'schema' => array($this,'get_params_schema'),
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
                    'args' => array(),
                ),
                array(
                    "methods" => \WP_REST_Server::READABLE,
                    "callback" => array($this, 'get_item'),
                    "permission_callback" => array($this, 'auth_get_item'),
                    'args' => array(),
                ),
            )
        );
    }

    public function get_item(\WP_REST_Request $request): object
    {
        $product = wc_get_product($request['id']);

        if (!$product) {
            throw new \Requests_Exception_HTTP_404(
                "product not found in database"
            );
        }

        $json = $product->get_data();

        return new \WP_REST_Response($json);
    }

    public function get_items(\WP_REST_Request $request): object
    {
        $args = $this->request_to_args($request);
        $results = (array)wc_get_products($args);

        $results['products'] = $this->remap_results_array($results['products']);

        return new \WP_REST_Response($results);
    }

    public function delete_item(\WP_REST_Request $request): object
    {
        $product = wc_get_product($request['id']);

        if (!$product) {
            throw new \Requests_Exception_HTTP_404(
                "product not found in database"
            );
        }

        $forceDelete = filter_var($request['force'], FILTER_VALIDATE_BOOLEAN);
        $childIds = $product->get_children();

        foreach ($childIds as $id) {
            $child = wc_get_product($id);
            if ($child instanceof \WC_Product) {
                $child->delete($forceDelete);
            }
        }

        return new \WP_REST_Response(array(
            'message' => $forceDelete ?
                'product permanently deleted successfullly!' :
                'product moved to trash successfully!',
            'data' => json_decode((string)$product, true),
        ));
    }

    public function get_item_schema(): array
    {
        $schema = array(
            'properties' => array(
                'id' => array(
                    'description' => 'Unique resource identifier within local woocommerce installation',
                    'type' => 'integer',
                    'context' => array('view', 'edit'),
                    'readonly' => true,
                ),
                //TODO: further develop the item schema
            )
        );

        return $schema;
    }

    public function get_params_schema(): array
    {
        $params = parent::get_params_schema();

        $factory = new PWP_Schema_Factory('default');
        $schema = new PWP_Json_Schema('product');

        $schema
            ->add_property(
                'sku',
                $factory->string_property('filter results to matching SKUs. supports partial matches.')
                    ->view()
            )
            ->add_property(
                'f2d-sku',
                $factory->string_property('filter result to matching F2D sku.')
                    ->view()
            )
            ->add_property(
                'limit',
                $factory->int_property('maximum amount of results per call')
                    ->default(self::PAGE_SOFT_CAP)
                    ->add_custom_arg('sanitize_callback', 'absint')
            )
            ->add_property(
                'page',
                $factory->int_property('when using pageination, represents the page of results to retrieve.')
                    ->default(1)
                    ->add_custom_arg('min', -1)
                    ->add_custom_arg('sanitize_callback', 'absint')
            )
            ->add_property(
                'order',
                $factory->enum_property('how to order; ascending or descending', array(
                    'ASC',
                    'DESC'
                ))->default('ASC')
            )
            ->add_property(
                'type',
                $factory->enum_property('types to match', array_keys(wc_get_product_types()))
                    ->default('simple')
            )
            ->add_property(
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
            );

        // $params['f2d-sku'] = array(
        //     'description' => 'filter results to matching F2D SKU. supports partial matches.',
        //     'type' => 'string',
        //     'validate_callback' => 'rest_validate_request_arg',
        // );

        // $params['tag'] = array(
        //     'description' => 'tags to match by slug',
        //     //array : limit specific tags by slug
        // );
        // $params['category'] = array(
        //     'description' => 'categories to match by slug',
        //     //array : limit categories by slug
        // );
        // $params['status'] = array(
        //     'description' => 'status to match',
        //     //string | array : draft; pending; private; publish; trash
        // );
        // $params['price'] = array(
        //     'description' => 'price to match',
        //     //float : price to match
        // );

        //TODO: keep building on schema. look towards WooCommerce Rest API for examples on structure

        return $schema->to_array();
    }

    protected function request_to_args(\WP_REST_Request $request): array
    {
        $args = new PWP_ArgBuilder(array(
            'return'        => 'objects',
            'limit'         => (int)$request['limit'] ?: self::PAGE_SOFT_CAP,
            'page'          => (int)$request['page'] ?: 1,
            'paginate'      => true,
        ));

        $args
            ->add_arg_if_exists($request, 'sku')
            ->add_arg_if_exists($request, 'f2d-sku')
            ->add_arg_if_exists($request, 'status')
            ->add_arg_if_exists($request, 'type')
            ->add_arg_if_exists($request, 'tag')
            ->add_arg_if_exists($request, 'category')
            ->add_arg_if_exists($request, 'price')
            ->add_arg_if_exists($request, 'orderby')
            ->add_arg_if_exists($request, 'order');

        return $args->to_array();
    }

    private function remap_results_array(array $products): array
    {
        return array_map(
            function ($product) {
                if (!$product instanceof \WC_Product) {
                    return $product;
                }
                $data = $product->get_data();
                $data['variations'] = $product->get_children();
                return $data;
            },
            $products
        );
    }
}
