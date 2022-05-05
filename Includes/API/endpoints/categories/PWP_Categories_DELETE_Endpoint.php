<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\API\endpoints\PWP_Abstract_DELETE_Endpoint;
use PWP\includes\handlers\commands\PWP_Category_Command_Factory;
use WP_REST_Response;

class PWP_Categories_DELETE_Endpoint extends PWP_Abstract_DELETE_Endpoint
{
    public function __construct(string $path, PWP_Authenticator $authenticator)
    {
        parent::__construct(
            $path .  "/(?P<slug>\w+)",
            'product categories',
            $authenticator
        );
    }

    final public function do_action(\WP_REST_Request $request): \WP_REST_Response
    {
        $slug = $request['slug'];
        $factory = new PWP_Category_Command_Factory();
        $command = $factory->new_delete_term_command($slug);
        return new WP_REST_Response($command->do_action()->to_array());
    }
}
