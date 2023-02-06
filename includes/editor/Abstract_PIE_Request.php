<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\Abstract_Request;
use PWP\includes\exceptions\Invalid_Response_Exception;

/**
 * Abstract base class for any request to the PIE editor
 */
abstract class Abstract_PIE_Request extends Abstract_Request
{
    private string $endpoint;
    private string $apiKey;
    private string $customerId;
    private string $method;
    private int $timeouts;

    /**
     * base class for any request to the PIE editor
     *
     * @param string $domain base PIE domain
     * @param string $endpoint API endpoint, relative to the domain
     * @param string $apiKey 
     * @param string $customerId
     */
    public function __construct(string $domain, string $endpoint, string $apiKey, string $customerId = '')
    {
        $this->endpoint = $domain . $endpoint;
        $this->apiKey = $apiKey;
        $this->customerId = $customerId;

        $this->timeouts = 5;
        $this->redirects = 5;
        $this->set_GET();
    }

    /**
     * Get full URL of the API endpoint this class calls
     *
     * @return string
     */
    final protected function get_endpoint_url(): string
    {
        return $this->endpoint;
    }

    final protected function get_api_key(): string
    {
        return $this->apiKey;
    }

    final protected function get_customer_id(): string
    {
        return $this->customerId;
    }

    final protected function set_GET(): void
    {
        $this->method = 'GET';
    }

    final protected function set_POST(): void
    {
        $this->method = 'POST';
    }

    final public function get_method(): string
    {
        return $this->method;
    }

    /**
     * Make request to endpoint
     *
     * @return object decoded response body object
     * @throws Invalid_Response_Exception on a `wp_error`/`null`/`false` response
     */
    public function make_request(): object
    {
        $response = wp_remote_get($this->endpoint, array(
            'method' => $this->method,
            'timeout' => $this->timeout,
            'redirection' => $this->redirects,
            'headers' => $this->generate_request_header(),
            'body' => $this->generate_request_body(),
        ));

        if (is_wp_error($response) || !$response) {
            throw new Invalid_Response_Exception(__("Could not connect to API.", 'Peleman-Webshop-Package'));
        }

        return (object)$response['body'];
    }

    protected abstract function generate_request_body(): array;

    /**
     * Generates basic request header with authentication
     *
     * @return array
     */
    protected function generate_request_header(): array
    {
        $referer = get_site_url();
        $headers = array(
            "PIEAPIKEY" => $this->apiKey,
            // "PROJECTNAME" => $this->projectName,
        );

        return $headers;
    }
}
