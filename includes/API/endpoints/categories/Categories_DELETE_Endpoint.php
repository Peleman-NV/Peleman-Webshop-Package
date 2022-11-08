<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use WP_REST_Response;
use PWP\includes\API\Channel_Definition;
use PWP\includes\authentication\I_Api_Authenticator;
use PWP\includes\API\endpoints\Abstract_DELETE_Endpoint;
use PWP\includes\handlers\commands\Category_Command_Factory;

class Categories_DELETE_Endpoint extends Abstract_DELETE_Endpoint
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

    final public function do_action(\WP_REST_Request $request): \WP_REST_Response
    {
        $slug = $request['slug'];
        $factory = new Category_Command_Factory();
        $command = $factory->new_delete_term_command($slug);
        return new WP_REST_Response($command->do_action()->to_array());
    }
}
