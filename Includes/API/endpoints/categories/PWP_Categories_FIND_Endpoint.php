<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use WP_REST_Response;
use PWP\includes\API\PWP_Channel_Definition;
use PWP\includes\authentication\PWP_I_Api_Authenticator;
use PWP\includes\API\endpoints\PWP_Abstract_FIND_Endpoint;
use PWP\includes\handlers\commands\PWP_Category_Command_Factory;

class PWP_Categories_FIND_Endpoint extends PWP_Abstract_FIND_Endpoint
{

    public function __construct(PWP_Channel_Definition $channel, PWP_I_Api_Authenticator $authenticator)
    {
        parent::__construct(
            $channel->get_namespace(),
            $channel->get_route() .  "/(?P<slug>\w+)",
            $channel->get_title(),
            $this->authenticator = $authenticator
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
