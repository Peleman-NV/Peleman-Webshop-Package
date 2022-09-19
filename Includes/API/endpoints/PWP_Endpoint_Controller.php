<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\PWP_I_Api_Authenticator;
use PWP\includes\exceptions\PWP_Invalid_Input_Exception;
use PWP\includes\hookables\abstracts\PWP_I_Hookable_Component;
use PWP\includes\loaders\PWP_Plugin_Loader;
use PWP\includes\utilities\response\PWP_Error_Response;

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

    public function get_arguments(): array
    {
        return [];
    }

    final public function register(): void
    {
        register_rest_route(
            $this->namespace,
            $this->get_path(),
            array(
                'args' => $this->get_arguments(),
                'callback' => $this->get_callback(),
                'methods' => $this->get_methods(),
                'permission_callback' => $this->get_permission_callback(),
            )
        );
    }

    final protected function validate_request_with_schema(array $request): array
    {
        $schema = $this->get_arguments();
        $result = rest_validate_value_from_schema($request, $schema);
        if (is_wp_error($result)) {
            throw new PWP_Invalid_Input_Exception(
                $result->get_error_message(),
            );
        }
        $request = rest_sanitize_value_from_schema($request, $schema);
        return $request;
    }

    public abstract function do_action(\WP_REST_Request $request): \WP_REST_Response;
    public abstract function authenticate(\WP_REST_Request $request): bool;
}
