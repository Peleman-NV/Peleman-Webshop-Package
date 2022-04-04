<?php

declare(strict_types=1);

namespace PPA\includes\endpoints;

use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use Requests_Exception_HTTP_404;
use PPA\includes\authentication\PPA_Authenticator;
use PPA\includes\endpoints\PPA_EndpointController;
use WC_Product;

class PPA_Products_Endpoint extends PPA_EndpointController
{
    private const PAGE_SOFT_CAP = 10;

    public function __construct(string $namespace, PPA_Authenticator $authenticator)
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
                    "permission_callback" => array($this, 'authenticate'),
                    'args' => $this->get_params(),
                ),
                array(
                    "methods" => WP_REST_Server::CREATABLE,
                    "callback" => array($this, 'create_item'),
                    "permission_callback" => array($this, 'authenticate'),
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
                    "permission_callback" => array($this, 'authenticate'),
                ),
                array(
                    "methods" => WP_REST_Server::READABLE,
                    "callback" => array($this, 'get_item'),
                    "permission_callback" => array($this, 'authenticate'),
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
        $pagination = isset($request['limit']);
        $results = (array)wc_get_products($this->request_to_args($request, $pagination));

        if ($pagination) {
            $results['products'] = $this->remap_array($results['products']);
        } else {
            $results = $this->remap_array($results);
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

    private function request_to_args(WP_REST_Request $request, bool $pagination): array
    {
        $arguments = array(
            'return' => 'objects',
            'sku' => $request['sku'] ?: "",
            'limit' => (int)$request['limit'] ?: self::PAGE_SOFT_CAP,
        );

        if ($pagination) {
            $arguments['paginate'] = true;
            $arguments['page'] = (int)$request['page'] ?: 1;
        }

        return $arguments;
    }

    private function remap_array(array $products): array
    {
        return array_map(
            function ($product) {
                if (!$product instanceof WC_Product) {
                    return $product;
                }
                return $product->get_data();
            },
            $products
        );
    }

    public function get_params(): array{
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
