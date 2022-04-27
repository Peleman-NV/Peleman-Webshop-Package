<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\PWP_IApiAuthenticator;

abstract class PWP_EndpointController implements PWP_I_Endpoint
{
    private string $path;
    private PWP_IApiAuthenticator $authenticator;

    public function __construct(string $path, string $title, PWP_IApiAuthenticator $authenticator)
    {
        $this->path = $path;
        $this->title = $title;
        $this->authenticator = $authenticator;
    }

    final public function get_callback(): callable
    {
        return array($this, 'do_action');
    }

    public function get_permission_callback(): callable
    {
        return array($this, 'authenticate');
    }

    public function get_path(): string
    {
        return $this->path;
    }

    public function get_authenticator(): PWP_IApiAuthenticator
    {
        return $this->authenticator;
    }

    public abstract function do_action(\WP_REST_Request $request): \WP_REST_Response;
    public abstract function authenticate(\WP_REST_Request $request): bool;
}
