<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use PWP\includes\API\endpoints\PWP_Abstract_FIND_Endpoint;
use PWP\includes\authentication\PWP_I_Api_Authenticator;
use PWP\includes\handlers\commands\PWP_Category_Command_Factory;
use WP_REST_Response;

class PWP_Categories_FIND_Endpoint extends PWP_Abstract_FIND_Endpoint
{

    public function __construct(string $namespace, string $path, PWP_I_API_Authenticator $authenticator)
    {
        parent::__construct(
            $namespace,
            $path .  "/(?P<slug>\w+)",
            'product category',
            $authenticator
        );
    }

    final public function do_action(\WP_REST_Request $request): \WP_REST_Response
    {
        $factory = new PWP_Category_Command_Factory();
        $data = $request->get_query_params();
        $command = $factory->new_read_term_command($data);
        return new WP_REST_Response($command->do_action()->to_array());
    }

    final public function get_arguments(): array
    {
        return [];
    }
}
