<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\API\Channel_Definition;
use PWP\includes\authentication\I_Api_Authenticator;
use PWP\includes\API\endpoints\Abstract_READ_Endpoint;
use PWP\includes\handlers\commands\Category_Command_Factory;

class Categories_READ_Endpoint extends Abstract_READ_Endpoint
{
    public function __construct(Channel_Definition $channel, I_Api_Authenticator $authenticator)
    {
        parent::__construct(
            $channel->get_namespace(),
            $channel->get_route(),
            $channel->get_title(),
            $this->authenticator = $authenticator
        );
    }

    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        $factory = new Category_Command_Factory();
        $data = $request->get_query_params();
        $command = $factory->new_read_term_command($data);
        return new WP_REST_Response($command->do_action()->to_array());
    }

    public function get_schema(): array
    {
        return [];
    }
}
