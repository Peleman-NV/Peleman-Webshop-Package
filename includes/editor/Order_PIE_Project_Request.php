<?php

declare(strict_types=1);

namespace PWP\includes\editor;

class Order_PIE_Project_Request extends Abstract_PIE_Request
{
    private int $orderId;
    private array $orderLines;

    public function __construct(string $domain, string $apiKey, string $customerId, int $orderId)
    {
        $endpoint = '/editor/api/updateordernr.php';
        parent::__construct($domain, $endpoint, $apiKey, $customerId);

        $this->orderId = $orderId;
        $this->orderLines = [];
    }

    public function add_order_line(string $orderLineId, string $projectId): self
    {
        $this->orderLines[$orderLineId] = $projectId;
        return $this;
    }

    protected function generate_request_body(): array
    {
        $response = array(
            'order_id' => $this->orderId,
            'order_lines' => [],
        );

        foreach ($this->orderLines as $line => $id) {
            $response['order_lines'][] = array(
                'order_line' => $line,
                'project_id' => $id,
            );
        }

        return $response;
    }
}
