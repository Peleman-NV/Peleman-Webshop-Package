<?php

declare(strict_types=1);

namespace PPA\includes\endpoints;

use PPA\includes\authentication\PPA_Authenticator;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

defined('ABSPATH') || die;

class PPA_Test_Endpoint extends PPA_Endpoint
{
    public function __construct(string $namespace, PPA_Authenticator $authenticator)
    {
        parent::__construct(
            $namespace,
            '/test',
            $authenticator
        );
    }

    public function register(): void
    {
        register_rest_route(
            $this->namespace,
            $this->rest_base,
            array(
                array(
                    'methods' => WP_REST_Server::ALLMETHODS,
                    'callback' => array($this, self::GET),
                    'permission_callback' => array($this, self::AUTH),
                ),
            ),
        );
    }

    protected function handle_get_callback(WP_REST_Request $request): object
    {
        return new WP_REST_Response('test successful!');
    }
}
