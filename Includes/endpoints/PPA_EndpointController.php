<?php

declare(strict_types=1);

namespace PPA\includes\endpoints;

use WP_Error;
use WP_REST_Request;
use WP_REST_Controller;
use PPA\includes\authentication\PPA_Authenticator;

defined('ABSPATH') || die;

abstract class PPA_EndpointController implements PPA_IEndpoint
{
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

    /**
     * GET individual item
     *
     * @param WP_REST_Request $request
     * @return object
     */
    public function get_item(WP_REST_Request $request): object
    {
        throw new \Requests_Exception_HTTP_501("method not implemented!");
    }

    /**
     * GET grouped items
     *
     * @param WP_REST_Request $request
     * @return object
     */
    public function get_items(WP_REST_Request $request): object
    {
        throw new \Requests_Exception_HTTP_501("method not implemented!");
    }

    /**
     * PUT/PATCH item
     *
     * @param WP_REST_Request $request
     * @return object
     */
    public function update_item(WP_REST_Request $request): object
    {
        throw new \Requests_Exception_HTTP_501("method not implemented!");
    }

    /**
     * POST new item
     *
     * @param WP_REST_Request $request
     * @return object
     */
    public function post_item(WP_REST_Request $request): object
    {
        throw new \Requests_Exception_HTTP_501("method not implemented!");
    }

    /**
     * DELETE item
     *
     * @param WP_REST_Request $request
     * @return object
     */
    public function delete_item(WP_REST_Request $request): object
    {
        throw new \Requests_Exception_HTTP_501("method not implemented!");
    }
    #endregion

    /**
     * Authentication function for API Endpoint controller
     * by default, uses the API authenticator class. In specific conditions, it might be worthwhile to overwrite this function to loosen or open up access for certain endpoints
     * @return boolean
     * @throws \Requests_exception_HTTP
     */
    public function authenticate(WP_REST_REQUEST $request): bool
    {
        return $this->authenticator->authenticate($request);
    }
}
