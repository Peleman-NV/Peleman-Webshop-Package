<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use WP_REST_Response;
use PWP\includes\wrappers\Term_Data;
use PWP\includes\API\Channel_Definition;
use PWP\includes\authentication\I_Api_Authenticator;
use PWP\includes\API\endpoints\Abstract_UPDATE_Endpoint;
use PWP\includes\handlers\commands\Category_Command_Factory;

class Categories_UPDATE_Endpoint extends Abstract_UPDATE_Endpoint
{
    public function __construct(Channel_Definition $channel, I_Api_Authenticator $authenticator)
    {
        parent::__construct(
            $channel->get_namespace(),
            $channel->get_route() .  "/(?P<slug>\w+)",
            $channel->get_title(),
            $this->authenticator = $authenticator
        );
    }

    final public function do_action(\WP_REST_Request $request): WP_REST_Response
    {
        $factory = new Category_Command_Factory();
        $data = new Term_Data($request->get_json_params());
        $command = $factory->new_update_term_command($data);
        return new WP_REST_Response($command->do_action()->to_array());
    }
}
