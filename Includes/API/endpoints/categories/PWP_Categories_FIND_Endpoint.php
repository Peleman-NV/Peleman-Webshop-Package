<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use PWP\includes\handlers\PWP_Category_Handler;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\API\endpoints\PWP_Abstract_FIND_Endpoint;
use PWP\includes\exceptions\PWP_API_Exception;
use PWP\includes\handlers\commands\PWP_Category_Command_Factory;
use PWP\includes\utilities\schemas\PWP_Schema_Factory;
use PWP\includes\wrappers\PWP_Term_Data;
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
