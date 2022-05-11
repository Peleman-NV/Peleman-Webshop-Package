<?php

declare(strict_types=1);

namespace PWP\includes\utilities\notification;

use PWP\includes\utilities\response\PWP_I_Response_Component;

class PWP_Success_Notice implements PWP_I_Notification_Message, PWP_I_Response_Component
{
    private string $message;
    private string $description;
    private array $data;

    public function __construct(string $message, string $description, array $data = [])
    {
        $this->message = $message;
        $this->description = $description;
        $this->data = $data;
    }

    public function get_message(): string
    {
        return $this->message;
    }

    public function get_description(): string
    {
        return $this->description;
    }

    public function get_data(): array
    {
        return $this->data;
    }

    public function to_array(): array
    {
        return array(
            "message" => $this->message,
            "description" => $this->description,
            "data" => $this->data,
        );
    }
}
