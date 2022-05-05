<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use PWP\includes\exceptions\PWP_API_Exception;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\API\endpoints\PWP_Abstract_UPDATE_Endpoint;
use PWP\includes\handlers\commands\PWP_Category_Command_Factory;
use PWP\includes\wrappers\PWP_Term_Data;

class PWP_Categories_UPDATE_Endpoint extends PWP_Abstract_UPDATE_Endpoint
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
            $factory = new PWP_Category_Command_Factory();
            $termData = new PWP_Term_Data($request->get_json_params());
            $response = $factory->new_update_term_command($termData)->do_action();

            return new \WP_REST_Response($response->to_array());
        } catch (PWP_API_Exception $exception) {
            return $exception->to_rest_response();
        }
    }

    final public function authenticate(\WP_REST_Request $request): bool
    {
        return true;
    }
}
