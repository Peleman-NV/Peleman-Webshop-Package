<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PTP\includes\sureTax\responses\PTP_I_Response;
use PWP\includes\Abstract_Request;
use PWP\includes\exceptions\Invalid_Response_Exception;

abstract class Abstract_PIE_Request extends Abstract_Request
{
    private string $endpoint;
    private string $apiKey;
    private string $customerId;
    private string $method;

    public function __construct(string $domain, string $endpoint, string $apiKey, string $customerId = '')
    {
        $this->endpoint = $domain . $endpoint;
        $this->apiKey = $apiKey;
        $this->customerId = $customerId;

        $this->timeouts = 5;
        $this->redirects = 5;
        $this->set_GET();
    }

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
            throw new Invalid_Response_Exception(__("Could not connect to API.", PWP_TEXT_DOMAIN));
        }

        return (object)$response['body'];
    }
    protected abstract function generate_request_body();

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
