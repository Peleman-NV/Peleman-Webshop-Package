<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints\categories;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\wrappers\Term_Data;
use PWP\includes\API\Channel_Definition;
use PWP\includes\utilities\SitePress_Wrapper;
use PWP\includes\utilities\schemas\Schema_Factory;
use PWP\includes\utilities\schemas\Argument_Schema;
use PWP\includes\authentication\I_Api_Authenticator;
use PWP\includes\API\endpoints\Abstract_CREATE_Endpoint;
use PWP\includes\handlers\commands\Category_Command_Factory;

class Categories_CREATE_Endpoint extends Abstract_CREATE_Endpoint
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
        $data = new Term_Data($request->get_body_params());
        $command = $factory->new_create_term_command($data);
        return new WP_REST_Response($command->do_action()->to_array());
    }

    function get_arguments(): array
    {
        $sitepress = new SitePress_Wrapper();
        $activeLanguages = $sitepress->get_active_languages();

        return [];
    }

    public function get_schema(): array
    {
        return [];
    }
}
