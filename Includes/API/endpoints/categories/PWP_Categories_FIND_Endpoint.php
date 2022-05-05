<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use PWP\includes\handlers\PWP_Category_Handler;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\API\endpoints\PWP_Abstract_FIND_Endpoint;
use PWP\includes\exceptions\PWP_API_Exception;
use WP_REST_Response;

class PWP_Categories_FIND_Endpoint extends PWP_Abstract_FIND_Endpoint
{

    public function __construct(string $path, PWP_Authenticator $authenticator)
    {
        parent::__construct(
            $path .  "/(?P<slug>\w+)",
            'product category',
            $authenticator
        );
    }

    final public function do_action(\WP_REST_Request $request): \WP_REST_Response
    {
        try {
            $handler = new PWP_Category_Handler();
            $response = $handler->get_item_by_slug($request['slug'], $request->get_query_params());
            return new WP_REST_Response($response->data);
        } catch (PWP_API_Exception $exception) {
            return $exception->to_rest_response();
        } catch (\Exception $exception) {
            return new \WP_REST_Response(array(
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'data' => $exception->getTraceAsString()
            ), $exception->getCode());
        }
    }

    final public function authenticate(\WP_REST_Request $request): bool
    {
        return true;
    }

    final public function get_arguments(): array
    {
        return [];
    }
}
