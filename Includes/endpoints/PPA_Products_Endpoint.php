<?php

declare(strict_types=1);

namespace PPA\includes\endpoints;

use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use Requests_Exception_HTTP_404;
use PPA\includes\authentication\PPA_IApiAuthenticator;
use PPA\includes\endpoints\PPA_EndpointController;
use WC_Product;

class PPA_Products_Endpoint extends PPA_EndpointController
{
    private const PAGE_SOFT_CAP = 10;

    public function __construct(string $namespace, PPA_IApiAuthenticator $authenticator)
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
                    'args' => $this->get_params(),
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
        $args = $this->request_to_args($request);
        $results = (array)wc_get_products($this->request_to_args($request));

        if (!empty($args['paginate'])) {
            $results['products'] = $this->remap_results_array($results['products']);
        } else {
            $results = $this->remap_results_array($results);
        }

        $results['test'] = "some data";

        return new WP_REST_Response($results);
    }

    public function delete_item(WP_REST_Request $request): object
    {
        $product = wc_get_product($request[$this->productId]);

        if (!$product) {
            throw new Requests_Exception_HTTP_404(
                "product not found in database"
            );
        }

        $forceDelete = filter_var($request['force'], FILTER_VALIDATE_BOOL);
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
        $args = array(
            'return'        => 'objects',
            'limit'         => (int)$request['limit'] ?: self::PAGE_SOFT_CAP,
            'page'          => (int)$request['page'] ?: 1,
            'paginate'      => (int)$request['limit'] > -1,
            // 'order'         => $request['order'],   //DESC or ASC
            // 'orderby'       => $request['orderby'], //valid strings are: none, ID, name, type, rand, date, modified
            //MORE TO BE ADDED IF NECESSARY
        );

        //string | array : draft; pending; private; publish; trash
        if(!empty($request['status'])) $args['status'] = $request['status'];
        //string | array : external; grouped; simple; variable; custom
        if(!empty($request['type'])) $args['type'] = $request['type'];
        //string : partial string match to SKU
        if(!empty($request['sku'])) $args['sku'] = $request['sku'];
        //array : limit specific tags by slug
        if(!empty($request['tag'])) $args['tag'] = $request['tag'];
        //array : limit categories by slug
        if(!empty($request['category'])) $args['category'] = $request['category'];
        //float : price to match
        if(!empty($request['price'])) $args['price'] = $request['price'];
        //string : ASC; DESC
        if(!empty($request['order'])) $args['order'] = $request['order'];
        //string : none; id; name; type; rand; date; modified;
        if(!empty($request['orderby'])) $args['orderby'] = $request['orderby'];
        
        return $args;
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

    public function get_params(): array
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
            'validate_callback' => 'rest_validate_request_arg',
        );
        $params['page'] = array(
            'description' => 'when using pageination, represents the page of results to retrieve',
            'type' => 'integer',
            'default' => 1,
            'validate_callback' => 'rest_validate_request_arg',
        );

        //TODO: further build schema

        return $params;
    }
}
