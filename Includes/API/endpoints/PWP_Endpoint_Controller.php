<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\PWP_I_Api_Authenticator;
use PWP\includes\hookables\abstracts\PWP_I_Hookable_Component;
use PWP\includes\loaders\PWP_Plugin_Loader;

abstract class PWP_Endpoint_Controller implements PWP_I_Endpoint, PWP_I_Hookable_Component
{
    protected string $namespace;
    protected string $path;
    protected string $title;
    protected PWP_I_Api_Authenticator $authenticator;

    protected string $object;

    public function __construct(string $namespace, string $path, string $title, PWP_I_Api_Authenticator $authenticator)
    {
        $this->namespace = $namespace;
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

    public function register_hooks(PWP_Plugin_Loader $loader): void
    {
        $loader->add_API_Endpoint($this->namespace, $this);
    }

    public abstract function do_action(\WP_REST_Request $request): \WP_REST_Response;
    public abstract function authenticate(\WP_REST_Request $request): bool;
}
