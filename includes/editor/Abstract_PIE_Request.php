<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PTP\includes\sureTax\responses\PTP_I_Response;
use PWP\includes\Abstract_Request;

abstract class Abstract_PIE_Request extends Abstract_Request
{
    private string $endpoint;
    private string $apiKey;
    private string $customerId;

    public function __construct(string $domain, string $endpoint, string $apiKey, string $customerId = '')
    {
        $this->endpoint = $domain . $endpoint;
        $this->apiKey = $apiKey;
        $this->customerId = $customerId;
    }

    protected function get_endpoint_url(): string
    {
        return $this->endpoint;
    }

    protected function get_api_key(): string
    {
        return $this->apiKey;
    }

    protected function get_customer_id(): string
    {
        return $this->customerId;
    }

    protected function generate_request_header(): array
    {
        return [];
    }

    protected function generate_request_query_array(): array
    {
        return [];
    }
}
