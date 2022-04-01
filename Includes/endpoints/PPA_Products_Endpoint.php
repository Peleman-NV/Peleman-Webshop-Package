<?php

declare(strict_types=1);

namespace PPA\includes\endpoints;

use PPA\includes\authentication\PPA_Authenticator;
use PPA\includes\endpoints\PPA_Endpoint;
use Requests_Exception_HTTP_404;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class PPA_Products_Endpoint extends PPA_Endpoint
{
    private string $productId;

    public function __construct(string $namespace, PPA_Authenticator $authenticator)
    {
        $this->productId = 'productId';

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
            $this->rest_base . "/(?P<productId>\d+)",
            array(
                array(
                    "methods" => WP_REST_Server::DELETABLE,
                    "callback" => array($this, self::DELETE),
                    "permission_callback" => array($this, self::AUTH),
                ),
                array(
                    "methods" => WP_REST_Server::READABLE,
                    "callback" => array($this, self::GET),
                    "permission_callback" => array($this, self::AUTH),

                ),
            )
        );
    }

    protected function handle_get_callback(WP_REST_Request $request): object
    {
        $product = wc_get_product($request[$this->productId]);

        if (!$product) {
            throw new Requests_Exception_HTTP_404(
                "product not found in database"
            );
        }

        $json = $product->get_data();

        return new WP_REST_Response($json);
    }

    protected function handle_delete_callback(WP_REST_Request $request): object
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
}
