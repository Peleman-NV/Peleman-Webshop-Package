<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\variations;

use PWP\includes\API\endpoints\PWP_Abstract_CREATE_Endpoint;
use PWP\includes\API\PWP_Channel_Definition;
use PWP\includes\authentication\PWP_I_Api_Authenticator;
use WP_REST_Request;
use WP_REST_Response;

class PWP_Variations_CREATE_Endpoint extends PWP_Abstract_CREATE_Endpoint
{
    public function __construct(PWP_Channel_Definition $channel, PWP_I_Api_Authenticator $authenticator)
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
        //TODO: create new product variation
        //key parameters to create a new variation should be as follows:
        // parent SKU: what product is the variable product we're modifying
        // product SKU: the product we are trying to create
        // product attributes: the attributes of the product we're creating
        // the concerning matter here is how to handle a duplicate variation
        // a duplicate variation will happen if there is a mismatch with the attributes
        // in an already existing variation of the same variable product
        // 
        return new WP_REST_Response('test - create variation endpoint');
    }
}
