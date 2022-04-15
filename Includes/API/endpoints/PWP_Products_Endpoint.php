<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\utilities\PWP_ArgBuilder;
use PWP\includes\utilities\schemas\PWP_Schema_Factory;
use PWP\includes\API\endpoints\PWP_EndpointController;
use PWP\includes\authentication\PWP_IApiAuthenticator;
use PWP\includes\handlers\PWP_Product_Handler;
use PWP\includes\utilities\schemas\PWP_Argument_Schema;
use PWP\includes\utilities\schemas\PWP_ISchema;
use PWP\includes\wrappers\product\PWP_Product;
use WP_REST_Request;
use WP_REST_Response;

class PWP_Products_Endpoint extends PWP_EndpointController implements PWP_IEndpoint
{
    private const PAGE_SOFT_CAP = 100;
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
                    'args' => $this->get_argument_schema()->to_array(),
                ),
                array(
                    "methods" => \WP_REST_Server::CREATABLE,
                    "callback" => array($this, 'create_item'),
                    "permission_callback" => array($this, 'auth_post_item'),
                    'args' => array(),
                ),
                'schema' => array($this, 'get_item_array')
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
        $args = new PWP_ArgBuilder();

        $args
            ->add_arg('return', 'objects')
            ->add_arg('paginate', true)
            ->add_arg_from_request($request, 'limit')
            ->add_arg_from_request($request, 'page')
            ->add_arg_from_request($request, 'SKU')
            ->add_arg_from_request($request, 'status')
            ->add_arg_from_request($request, 'type')
            ->add_arg_from_request($request, 'tag')
            ->add_arg_from_request($request, 'category')
            ->add_arg_from_request($request, 'price')
            ->add_arg_from_request($request, 'orderby')
            ->add_arg_from_request($request, 'order');

        $results = (array)wc_get_products($args->to_array());

        $results['products'] = $this->remap_results_array($results['products']);

        return new \WP_REST_Response(
            $results
        );
    }

    public function create_item(WP_REST_Request $request): object
    {
        try {
            $args = new PWP_ArgBuilder();
            $args
                ->add_required_arg_from_request($request, 'name')
                ->add_required_arg_from_request($request, 'SKU')
                ->add_arg_from_request($request, 'type', 'simple')
                ->add_arg_from_request($request, 'visibility', 'hidden')
                ->add_arg_from_request($request, 'description')
                ->add_arg_from_request($request, 'short_description')
                ->add_arg_from_request($request, 'attributes')
                ->add_arg_from_request($request, 'categories')
                ->add_arg_from_request($request, 'tags')
                ->add_arg_from_request($request, 'regular_price');

            $product = new PWP_Product($args->to_array());
            if (!$product->is_SKU_unique()) {
                throw new \Exception('product with this SKU already exists in the database!', 400);
            }

            $result = $product->save_to_product();
        } catch (\Exception $exception) {
            return new WP_REST_Response($exception->getMessage(), $exception->getCode());
        }

        return new WP_REST_Response(array(
            'good job! here are your args:',
            $args->to_array(),
            $result->get_data(),
        ));
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

    protected function get_argument_schema(): PWP_ISchema
    {
        $factory = new PWP_Schema_Factory('default');
        $schema = new PWP_Argument_Schema();
        $schema
            ->add_property(
                'SKU',
                $factory->string_property('filter results to matching SKUs. supports partial matches.')
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
            )
            ->add_property(
                'tag',
                $factory->array_property('limit results to specific tags by slug')
            )
            ->add_property(
                'category',
                $factory->array_property('limit results to specific categories by slug')
            )
            ->add_property(
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
