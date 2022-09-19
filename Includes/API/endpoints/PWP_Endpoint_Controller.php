<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use PWP\includes\authentication\PWP_I_Api_Authenticator;
use PWP\includes\exceptions\PWP_Invalid_Input_Exception;
use PWP\includes\hookables\abstracts\PWP_I_Hookable_Component;
use WP_REST_Controller;

abstract class PWP_Endpoint_Controller extends WP_REST_Controller implements PWP_I_Endpoint, PWP_I_Hookable_Component
{
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


    final public function register(): void
    {
        register_rest_route(
            $this->namespace,
            $this->get_path(),
            array(
                array(
                    'methods' => $this->get_methods(),
                    'callback' => $this->get_callback(),
                    'permission_callback' => $this->get_permission_callback(),
                    'args' => $this->get_arguments(),
                ),
                'schema' => array($this, 'get_schema'),
            ),
        );
    }

    /**
     * helper function for validing REST api requests with a json schema,
     * combining the functionality of both the 
     * `rest_validate_value_from_schema` and `rest_sanitize_value_from_schema` methods
     *
     * @param array $request request array body
     * @param string $name name of the object to be used in error/validation messages
     * @return array returns validated & sanitized request array
     * @throws PWP_Invalid_Input_Exception thrown if validation fails due to missing or incorrect parameter(s)
     */
    final protected function validate_request_with_schema(array $request, string $name = ''): array
    {
        $schema = $this->get_arguments();
        $result = rest_validate_value_from_schema($request, $schema, $name);
        if (is_wp_error($result)) {
            throw new PWP_Invalid_Input_Exception($result->get_error_message());
        }

        $request = rest_sanitize_value_from_schema($request, $schema, $name);
        if (is_wp_error($request)) {
            throw new PWP_Invalid_Input_Exception($request->get_error_message());
        }

        return $request;
    }

    public function get_arguments(): array
    {
        return [];
    }
    public abstract function do_action(\WP_REST_Request $request): \WP_REST_Response;
    public abstract function get_schema(): array;
    public abstract function authenticate(\WP_REST_Request $request): bool;
}
