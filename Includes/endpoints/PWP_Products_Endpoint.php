<?php

declare(strict_types=1);

namespace PWP\includes\endpoints;

use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use Requests_Exception_HTTP_404;
use PWP\includes\authentication\PWP_IApiAuthenticator;
use PWP\includes\endpoints\PWP_EndpointController;
use PWP\includes\PWP_ArgBuilder;
use WC_Product;

class PWP_Products_Endpoint extends PWP_EndpointController
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
                    "methods" => WP_REST_Server::READABLE,
                    "callback" => array($this, 'get_items'),
                    "permission_callback" => array($this, 'auth_get_items'),
                    'args' => $this->get_params_schema(),
                ),
                array(
                    "methods" => WP_REST_Server::CREATABLE,
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
                    "methods" => WP_REST_Server::DELETABLE,
                    "callback" => array($this, 'delete_item'),
                    "permission_callback" => array($this, 'auth_delete_item'),
                ),
                array(
                    "methods" => WP_REST_Server::READABLE,
                    "callback" => array($this, 'get_item'),
                    "permission_callback" => array($this, 'auth_get_item'),
                ),
            )
        );
    }

    public function get_item(WP_REST_Request $request): object
    {
        $product = wc_get_product($request['id']);

        if (!$product) {
            throw new Requests_Exception_HTTP_404(
                "product not found in database"
            );
        }

        $json = $product->get_data();

        return new WP_REST_Response($json);
    }

    public function get_items(WP_REST_Request $request): object
    {
        $results = (array)wc_get_products($this->request_to_args($request));

        $results['products'] = $this->remap_results_array($results['products']);

        return new WP_REST_Response($results);
    }

    public function delete_item(WP_REST_Request $request): object
    {
        $product = wc_get_product($request['id']);

        if (!$product) {
            throw new Requests_Exception_HTTP_404(
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

        return new WP_REST_Response(array(
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

    protected function request_to_args(WP_REST_Request $request): array
    {
        $args = new PWP_ArgBuilder(array(
            'return'        => 'objects',
            'limit'         => (int)$request['limit'] ?: self::PAGE_SOFT_CAP,
            'page'          => (int)$request['page'] ?: 1,
            'paginate'      => true,
        ));

        $args
            ->add_arg_if_exists($request, 'sku')
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
                if (!$product instanceof WC_Product) {
                    return $product;
                }
                $data = $product->get_data();
                $data['variations'] = $product->get_children();
                return $data;
            },
            $products
        );
    }

    public function get_params_schema(): array
    {
        $params = array();
        $params['sku'] = array(
            'description' => 'filter results to matching SKUs. supports partial matches.',
            'type' => 'string',
            'validate_callback' => 'rest_validate_request_arg',
        );
        $params['limit'] = array(
            'description' => 'maximum amount of results per call',
            'type' => 'integer',
            'default' => self::PAGE_SOFT_CAP,
            'minimum' => -1,
            'santize_callback' => 'absint',
            'validate_callback' => 'rest_validate_request_arg',
        );
        $params['page'] = array(
            'description' => 'when using pageination, represents the page of results to retrieve',
            'type' => 'integer',
            'default' => 1,
            'minimum' => 1,
            'sanitize_callback' => 'absint',
            'validate_callback' => 'rest_validate_request_arg',
        );
        $params['type'] = array(
            'description' => 'types to match',
            //string | array : external; grouped; simple; variable; custom
        );
        $params['order'] = array(
            'description' => 'how to order; ascending or descending order',
            //string : ASC; DESC
        );
        $params['orderby'] = array(
            'description' => 'by which parameter to order the resulting output',
            //string : none; id; name; type; rand; date; modified;
        );
        $params['tag'] = array(
            'description' => 'tags to match by slug',
            //array : limit specific tags by slug
        );
        $params['category'] = array(
            'description' => 'categories to match by slug',
            //array : limit categories by slug
        );
        $params['status'] = array(
            'description' => 'status to match',
            //string | array : draft; pending; private; publish; trash
        );
        $params['price'] = array(
            'description' => 'price to match',
            //float : price to match
        );

        //TODO: keep building on schema. look towards WooCommerce Rest API for examples on structure

        return $params;
    }
}
