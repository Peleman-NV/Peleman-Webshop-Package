<?php

declare(strict_types=1);

namespace PPA\includes\endpoints;

use WP_Error;
use WP_REST_Request;
use WP_REST_Controller;
use PPA\includes\authentication\PPA_Authenticator;

defined('ABSPATH') || die;

abstract class PPA_Endpoint extends WP_REST_Controller implements PPA_IEndpoint
{
    /**
     * const string for registering GET callbacks in Wordpress REST APIs.
     * 
     * example: ```'callback' => array($this, self::GET)```
     */
    protected const GET = 'get_callback';
    /**
     * const string for registering PUT and PATCH callbacks in Wordpress REST APIs.
     * 
     * example: ```'callback' => array($this, self::UPDATE)```
     */
    protected const UPDATE = 'update_callback';
    /**
     * const string for registering POST callbacks in Wordpress REST APIs.
     * 
     * example: ```'callback' => array($this, self::UPDATE)```
     */
    protected const POST = 'post_callback';
    /**
     * const string for registering DELETE callbacks in Wordpress REST APIs.
     * 
     * example: ```'callback' => array($this, self::UPDATE)```
     */
    protected const DELETE = 'delete_callback';

    /**
     * use when registering REST routes
     * 
     * example: ```'permission_callback' => array($this, self::AUTH)```
     */
    protected const AUTH = 'authenticate';

    private PPA_Authenticator $authenticator;

    /**
     * initialization function that registers this class' callback to the hook and rest API
     */
    public abstract function register(): void;

    public function __construct(string $namespace, string $rest_base, PPA_Authenticator $authenticator)
    {
        $this->namespace = $namespace;
        $this->rest_base = $rest_base;
        $this->authenticator = $authenticator;
    }

    #region Callback template functions
    final public function get_callback(WP_REST_Request $request): object
    {
        try {
            return rest_ensure_response($this->handle_get_callback($request));
        } catch (\Requests_Exception_HTTP $exception) {
            return $this->Exception_to_error($exception);
        }
    }

    final public function update_callback(WP_REST_Request $request): object
    {
        try {
            return rest_ensure_response($this->handle_update_callback($request));
        } catch (\Requests_Exception_HTTP $exception) {
            return $this->Exception_to_error($exception);
        }
    }

    final public function post_callback(WP_REST_Request $request): object
    {
        try {
            return rest_ensure_response($this->handle_post_callback($request));
        } catch (\Requests_Exception_HTTP $exception) {
            return $this->Exception_to_error($exception);
        }
    }

    final public function delete_callback(WP_REST_Request $request): object
    {
        try {
            return rest_ensure_response($this->handle_delete_callback($request));
        } catch (\Requests_Exception_HTTP $exception) {
            return $this->Exception_to_error($exception);
        }
    }
    #endregion

    #region overwriteable callback functions
    /**
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_ERROR
     * @throws \Requests_exception_HTTP
     */
    protected function handle_post_callback(WP_REST_Request $request): object
    {
        throw new \Requests_Exception_HTTP_501("method not implemented!");
    }

    /**
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_ERROR
     * @throws \Requests_exception_HTTP
     */
    protected function handle_get_callback(WP_REST_Request $request): object
    {
        throw new \Requests_Exception_HTTP_501("method not implemented!");
    }

    /**
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_ERROR
     * @throws \Requests_exception_HTTP
     */
    protected function handle_delete_callback(WP_REST_Request $request): object
    {
        throw new \Requests_Exception_HTTP_501("method not implemented!");
    }

    /**
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_ERROR
     * @throws \Requests_exception_HTTP
     */
    protected function handle_update_callback(WP_REST_Request $request): object
    {
        throw new \Requests_Exception_HTTP_501("method not implemented!");
    }
    #endregion

    /**
     * @return boolean
     * @throws \Requests_exception_HTTP
     */
    final public function authenticate(WP_REST_REQUEST $request): bool
    {
        return $this->authenticator->authenticate($request);
    }

    private function Exception_to_error(\Requests_Exception_HTTP $exception): \WP_Error
    {
        return new WP_Error(
            $exception->getCode(),
            $exception->getMessage(),
            $exception->getData()
        );
    }
}
