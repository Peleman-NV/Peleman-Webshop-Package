<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use WP_REST_Request;
use WP_REST_Response;

use PWP\includes\authentication\PWP_I_Api_Authenticator;
use PWP\includes\API\endpoints\PWP_Abstract_READ_Endpoint;
use PWP\includes\handlers\commands\PWP_Category_Command_Factory;

class PWP_Categories_READ_Endpoint extends PWP_Abstract_READ_Endpoint
{
    public function __construct(string $path, PWP_I_Api_Authenticator $authenticator)
    {
        parent::__construct(
            $path,
            'product category',
            $this->authenticator = $authenticator
        );
    }

    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        $factory = new PWP_Category_Command_Factory();
        $data = $request->get_query_params();
        $command = $factory->new_read_term_command($data);
        return new WP_REST_Response($command->do_action()->to_array());
    }
}
