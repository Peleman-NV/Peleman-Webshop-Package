<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use WP_REST_Response;
use PWP\includes\API\PWP_Channel_Definition;
use PWP\includes\authentication\PWP_I_API_Authenticator;
use PWP\includes\API\endpoints\PWP_Abstract_DELETE_Endpoint;
use PWP\includes\handlers\commands\PWP_Category_Command_Factory;

class PWP_Categories_UNPARENT_Endpoint extends PWP_Abstract_DELETE_Endpoint
{
    public function __construct(PWP_Channel_Definition $channel, PWP_I_Api_Authenticator $authenticator)
    {
        parent::__construct(
            $channel->get_namespace(),
            $channel->get_route() .  "/unparent",
            $channel->get_title(),
            $this->authenticator = $authenticator
        );
    }

    final public function do_action(\WP_REST_Request $request): \WP_REST_Response
    {
        $factory = new PWP_Category_Command_Factory();
        $terms = $factory->get_service()->get_items();
        foreach ($terms as $term) {
            $factory->get_service()->unparent_term($term);
        }
        return new WP_REST_Response("all parents unset in {$factory->get_service()->get_taxonomy_name()}");
    }
}
