<?php

declare(strict_types=1);

namespace PWP\includes\utilities\notification;

use PWP\includes\utilities\response\PWP_I_Response_Component;

class PWP_Error_Notice implements PWP_I_Notification_Message, PWP_I_Response_Component
{
    private string $message;
    private string $description;
    private ?\Exception $cause;

    public function __construct(string $message, string $description, ?\Exception $cause = null)
    {
        $this->message = $message;
        $this->description = $description;
        $this->cause = $cause;
    }

    public function get_message(): string
    {
        return $this->message;
    }

    public function get_description(): string
    {
        return $this->description;
    }

    public function get_cause(): \Exception
    {
        return $this->cause;
    }

    public function to_array(): array
    {
        $data = array(
            "error" => $this->message,
            "description" => $this->description,
        );

        if (!is_null($this->cause)) {
            $data['cause'] = array(
                'message' => $this->cause->getMessage(),
                'code' => $this->cause->getCode(),
            );
        }
        return $data;
    }
}
