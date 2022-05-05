<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\API\endpoints\PWP_Abstract_DELETE_Endpoint;
use PWP\includes\handlers\commands\PWP_Category_Command_Factory;
use WP_REST_Response;

class PWP_Categories_UNPARENT_Endpoint extends PWP_Abstract_DELETE_Endpoint
{
    public function __construct(string $path, PWP_Authenticator $authenticator)
    {
        parent::__construct(
            $path .  "/unparent",
            'product categories',
            $authenticator
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
