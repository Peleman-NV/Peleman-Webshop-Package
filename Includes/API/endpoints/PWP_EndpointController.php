<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\PWP_I_Api_Authenticator;

abstract class PWP_EndpointController implements PWP_I_Endpoint
{
    protected string $path;
    protected string $title;
    protected PWP_I_Api_Authenticator $authenticator;

    protected string $object;

    public function __construct(string $path, string $title, PWP_I_Api_Authenticator $authenticator)
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

    public function get_authenticator(): PWP_I_Api_Authenticator
    {
        return $this->authenticator;
    }

    public abstract function do_action(\WP_REST_Request $request): \WP_REST_Response;
    public abstract function authenticate(\WP_REST_Request $request): bool;
}
