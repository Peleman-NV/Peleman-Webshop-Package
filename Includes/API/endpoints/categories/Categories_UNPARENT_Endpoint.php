<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use WP_REST_Response;
use PWP\includes\API\Channel_Definition;
use PWP\includes\authentication\I_API_Authenticator;
use PWP\includes\API\endpoints\Abstract_DELETE_Endpoint;
use PWP\includes\handlers\commands\Category_Command_Factory;

class Categories_UNPARENT_Endpoint extends Abstract_DELETE_Endpoint
{
    public function __construct(Channel_Definition $channel, I_Api_Authenticator $authenticator)
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
        $factory = new Category_Command_Factory();
        $terms = $factory->get_service()->get_items();
        foreach ($terms as $term) {
            $factory->get_service()->unparent_term($term);
        }
        return new WP_REST_Response("all parents unset in {$factory->get_service()->get_taxonomy_name()}");
    }
}
