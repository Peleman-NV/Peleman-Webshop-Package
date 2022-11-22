<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\exceptions\Invalid_Response_Exception;

class Complete_PIE_Project_Request extends Abstract_PIE_Request
{
    private string $projectId;
    private string $orderId;
    private string $outputType;

    public function __construct(string $domain, string $apiKey, string $customerId = '')
    {
        parent::__construct($domain, '/editor/api/addtoqueueAPI.php', $apiKey, $customerId);
        $this->projectId = '';
        $this->orderId = '';
        $this->outputType = 'print';
    }

    public function set_project_id(string $id): self
    {
        $this->projectId = $id;
        return $this;
    }

    /**
     * sets order id value
     *
     * @param string $id
     * @return self
     */
    public function set_order_id(string $id): self
    {
        $this->orderId = $id;
        return $this;
    }

    public function set_output_type(string $type): self
    {
        $this->outputType = $type;
        return $this;
    }

    protected function generate_request_body(): array
    {
        $request = array(
            'customerid'        => $this->get_customer_id(),
            'customerapikey'    => $this->get_customer_id(),
            'projectid'         => $this->projectId,
            "orderid"           => $this->orderId,
            'outputtype'        => $this->outputType,
            'type'              => 'default',
        );
        return $request;
    }
}
