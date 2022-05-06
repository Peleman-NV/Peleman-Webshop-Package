<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_REST_Request;
use WP_REST_Response;
use PWP\includes\API\endpoints\PWP_EndpointController;
use PWP\includes\authentication\PWP_I_Api_Authenticator;
use PWP\includes\exceptions\PWP_API_Exception;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\validation\PWP_Validate_Throw_Exception;
use PWP\includes\wrappers\PWP_Term_Data;
use WCML\MultiCurrency\ExchangeRateServices\Service;

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
