<?php

declare(strict_types=1);

namespace PWP\includes\API\endpoints;

use WP_Error;
use WP_REST_Request;
use PWP\includes\API\endpoints\PWP_IEndpoint;
use PWP\includes\authentication\PWP_Authenticator;
use PWP\includes\authentication\PWP_IApiAuthenticator;
use PWP\includes\utilities\schemas\PWP_Argument_Schema;
use PWP\includes\utilities\schemas\PWP_ISchema;
use PWP\includes\utilities\schemas\PWP_Resource_Schema;

abstract class PWP_EndpointController implements PWP_IEndpoint, PWP_IApiAuthenticator
{
    private PWP_Authenticator $authenticator;
    private string $title;

    /**
     * initialization function that registers this class' callback to the hook and rest API
     */
    public abstract function register_routes(): void;

    public function __construct(string $namespace,  PWP_Authenticator $authenticator, string $rest_base, string $title)
    {
        $this->namespace = $namespace;
        $this->authenticator = $authenticator;
        $this->rest_base = $rest_base;
        $this->title = $title;
    }

    #region Callback template functions

    /**
     * POST new item
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function create_item(WP_REST_Request $request): object
    {
        throw new \Requests_Exception_HTTP_501("method not implemented!");
    }

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
     * @param WP_REST_Request $request
     * @return boolean
     * @throws \Requests_exception_HTTP
     */
    public function auth_post_item(WP_REST_Request $request): bool
    {
        return $this->authenticator->auth_post_item($request);
    }
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
    public function auth_update_item(WP_REST_Request $request): bool
    {
        return $this->authenticator->auth_update_item($request);
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
    #endregion

    /**
     * get item's resource schema.
     * schema indicates what fields are present for the particular item
     *
     * @return PWP_ISchema
     */
    protected function get_item_schema(): PWP_ISchema
    {
        return new PWP_Resource_Schema($this->title);
    }

    /**
     * get item's resource schema as array
     *
     * @return array
     */
    public function get_item_array(): array
    {
        return $this->get_item_schema()->to_array();
    }

    /**
     * get item's argument schema
     * schema indicates what fields are available for the request arguments
     *
     * @return PWP_ISchema
     */
    protected function get_argument_schema(): PWP_ISchema
    {
        return new PWP_Argument_Schema();
    }

    /**
     * get item's argument schema as array
     *
     * @return array
     */
    public function get_argument_array(): array
    {
        return $this->get_argument_schema()->to_array();
    }
}
