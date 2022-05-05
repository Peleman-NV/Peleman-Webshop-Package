<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\API\endpoints\PWP_EndpointController;
use PWP\includes\authentication\PWP_I_Api_Authenticator;
use PWP\includes\handlers\services\PWP_Term_SVC;

defined('ABSPATH') || die;

class PWP_Test_Endpoint extends PWP_EndpointController
{
    public function __construct(PWP_I_Api_Authenticator $authenticator)
    {
        parent::__construct(
            '/test',
            'test',
            $this->authenticator = $authenticator
        );
    }

    public function do_action(WP_REST_Request $request): WP_REST_Response
    {
        $service = new PWP_Term_SVC('product_cat', 'tax_product_cat', "product category");
        // var_dump($service);
        $service->disable_sitepress_get_term_filter();
        $results = $service->get_item_by_slug("my_testing_cat_003");
        $results = $service->get_item_by_slug("my_testing_cat_003-de");
        $service->enable_sitepress_get_term_filter();

        return new WP_REST_Response('test successful!', 200);
        // return new WP_REST_Response($results, 200);
    }

    public function authenticate(WP_REST_Request $request): bool
    {
        return true;
    }

    public function get_arguments(): array
    {
        return [];
    }

    public function get_methods(): string
    {
        return \WP_REST_Server::ALLMETHODS;
    }
}
