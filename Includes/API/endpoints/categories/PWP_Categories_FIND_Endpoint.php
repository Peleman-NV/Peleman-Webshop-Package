<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\API\endpoints\PWP_Abstract_FIND_Endpoint;
use PWP\includes\exceptions\PWP_Not_Implemented_Exception;

class PWP_Categories_FIND_Endpoint extends PWP_Abstract_FIND_Endpoint
{

    public function __construct(string $path, PWP_Authenticator $authenticator)
    {
        parent::__construct(
            $path .  "/categories/(?P<slug>\w+)",
            'product categories',
            $authenticator
        );
    }

    final public function do_action(\WP_REST_Request $request): \WP_REST_Response
    {
        return new \WP_REST_Response("we're not quite there yet, but we will be soon!", 501);
    }

    final public function authenticate(\WP_REST_Request $request): bool
    {
        throw new PWP_Not_Implemented_Exception(__METHOD__);
    }

    final public function get_arguments(): array
    {
        return [];
    }
}
