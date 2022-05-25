<?php

declare(strict_types=1);

namespace PWP\includes\utilities\response;

use Exception;
use PWP\includes\utilities\notification\PWP_Notification;

class PWP_Ajax_Response extends PWP_Notification
{
    private array $payload;

    public function set_payload(array $payload): void
    {
        $this->payload = $payload;
    }

    public function to_array(): array
    {
        $response = self::to_array();
        $response['status'] = $this->is_success() ? 'success' : 'failure';
        $response['success'] = $this->is_success();
        $response['payload'] = (array)$this->payload;

        return $response;
    }
}
