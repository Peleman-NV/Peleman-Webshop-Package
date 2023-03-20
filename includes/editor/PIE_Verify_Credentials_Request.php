<?php

declare(strict_types=1);

namespace PWP\includes\editor;

class PIE_Verify_Credentials_Request extends Abstract_PIE_Request
{
    public function __construct(string $domain, string $apiKey, string $customerId)
    {
        $endpoint = '/editor/api/getcustomerbyid.php';
        parent::__construct(
            $domain,
            $endpoint,
            $apiKey,
            $customerId,
        );
        $this->set_GET();
    }

    protected function generate_request_body(): array
    {
        return ['customerId' => $this->get_customer_id()];
    }
}
