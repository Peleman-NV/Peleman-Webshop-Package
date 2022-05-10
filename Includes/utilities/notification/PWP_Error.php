<?php

declare(strict_types=1);

namespace PWP\includes\utilities\notification;

class PWP_Error
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

    public function get_cause(): \Exception;
    {
        return $this->cause;
    }
}
