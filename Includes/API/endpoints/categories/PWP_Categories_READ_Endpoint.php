<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use WP_Term;
use WP_REST_Request;
use WP_REST_Response;

use PWP\includes\exceptions\PWP_API_Exception;
use PWP\includes\handlers\PWP_Category_Handler;
use PWP\includes\utilities\schemas\PWP_ISchema;
use PWP\includes\authentication\PWP_IApiAuthenticator;
use PWP\includes\utilities\schemas\PWP_Schema_Factory;
use PWP\includes\API\endpoints\PWP_Abstract_READ_Endpoint;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;
use PWP\includes\utilities\schemas\PWP_Argument_Schema;

class PWP_Categories_READ_Endpoint extends PWP_Abstract_READ_Endpoint
{
    public function __construct(string $path, PWP_IApiAuthenticator $authenticator)
    {
        parent::__construct(
            $path,
            'product category',
            $this->authenticator = $authenticator
        );
    }

    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        $handler = new PWP_Category_Handler();
        return new WP_REST_Response($handler->get_items($request->get_params()));
    }

    public function create_item(WP_REST_Request $request): WP_REST_Response
    {
        try {
            $handler = new PWP_Category_Handler();
            $response = $handler->create_item($request['name'], $request->get_body_params());

            if ($response instanceof WP_Term) {
                return new WP_REST_RESPONSE($response->data);
            }
        } catch (\Exception $exception) {
            return new WP_REST_Response(array(
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ), $exception->getCode());
        }
    }
}
