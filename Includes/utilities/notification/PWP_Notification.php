<?php

declare(strict_types=1);

namespace PWP\includes\utilities\notification;

use PWP\includes\utilities\response\PWP_I_Response_Component;

class PWP_Notification implements PWP_I_Notification, PWP_I_Response_Component
{
    private array $errors;
    private bool $isSuccess;

    public function __construct()
    {
        $this->success = null;
        $this->errors = array();
        $this->warnings = array();
        $this->isSuccess = true;
    }

    public function add_error(string $error, string $description, \Exception $cause = null): self
    {
        $this->errors[] = new PWP_Error_Notice($error, $description, $cause);
        $this->set_failed();
        return $this;
    }

    public function get_errors(): array
    {
        return $this->errors;
    }

    protected function set_failed(): void
    {
        $this->isSuccess = false;
    }

    public function is_success(): bool
    {
        return $this->isSuccess;
    }

    public function to_array(): array
    {
        $response = array();

        foreach ($this->errors as $key => $error) {
            $response['errors'][$key] = $error->to_array();
        }

        return $response;
    }
}
