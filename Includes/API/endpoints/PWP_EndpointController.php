<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_Error;
use WP_REST_Request;
use PWP\includes\API\endpoints\PWP_IEndpoint;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\authentication\PWP_IApiAuthenticator;

defined('ABSPATH') || die;

abstract class PWP_EndpointController implements PWP_IEndpoint, PWP_IApiAuthenticator
{
    private PWP_Authenticator $authenticator;

    /**
     * initialization function that registers this class' callback to the hook and rest API
     */
    public abstract function register(): void;

    public function __construct(string $namespace, string $rest_base, PWP_Authenticator $authenticator)
    {
        $this->namespace = $namespace;
        $this->rest_base = $rest_base;
        $this->authenticator = $authenticator;
    }

    #region Callback template functions

    /**
     * GET individual item
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function get_item(WP_REST_Request $request): object
    {
        throw new \Requests_Exception_HTTP_501("method not implemented!");
    }

    /**
     * GET grouped items
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function get_items(WP_REST_Request $request): object
    {
        throw new \Requests_Exception_HTTP_501("method not implemented!");
    }

    /**
     * PUT/PATCH item
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function update_item(WP_REST_Request $request): object
    {
        throw new \Requests_Exception_HTTP_501("method not implemented!");
    }

    /**
     * POST new item
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function post_item(WP_REST_Request $request): object
    {
        throw new \Requests_Exception_HTTP_501("method not implemented!");
    }

    /**
     * DELETE item
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function delete_item(WP_REST_Request $request): object
    {
        throw new \Requests_Exception_HTTP_501("method not implemented!");
    }
    #endregion
    #region REST AUTHENTICATION
    /**
     * Authentication function for API Endpoint controller
     * by default, uses the API authenticator class. In specific conditions, it might be worthwhile to overwrite this function to loosen or open up access for certain endpoints
     * @return boolean
     * @throws \Requests_exception_HTTP
     */
    public function auth_get_item(WP_REST_Request $request): bool
    {
        return $this->authenticator->auth_get_item($request);
    }

    /**
     * @param WP_REST_Request $request
     * @return boolean
     * @throws \Requests_exception_HTTP
     */
    public function auth_get_items(WP_REST_Request $request): bool
    {
        return $this->authenticator->auth_get_items($request);
    }

    /**
     * @param WP_REST_Request $request
     * @return boolean
     * @throws \Requests_exception_HTTP
     */
    public function auth_delete_item(WP_REST_Request $request): bool
    {
        return $this->authenticator->auth_delete_item($request);
    }

    /**
     * @param WP_REST_Request $request
     * @return boolean
     * @throws \Requests_exception_HTTP
     */
    public function auth_post_item(WP_REST_Request $request): bool
    {
        return $this->authenticator->auth_post_item($request);
    }

    /**
     * @param WP_REST_Request $request
     * @return boolean
     * @throws \Requests_exception_HTTP
     */
    public function auth_update_item(WP_REST_Request $request): bool
    {
        return $this->authenticator->auth_update_item($request);
    }
    #endregion
    public function get_params_schema() : array
    {
        return array();
    }
}
