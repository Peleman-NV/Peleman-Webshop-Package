<?php

declare(strict_types=1);

namespace PWP\includes\utilities\response;

use PWP\includes\utilities\notification\PWP_I_Notification;

class PWP_Response implements PWP_I_Response, PWP_I_Response_Component
{
    protected string $message;
    private array $data;
    private int $httpCode;
    /**
     * @var PWP_I_Response_Component[]
     */
    private array $components;


    public function __construct(string $message, bool $success = true, array $additionalData = [])
    {
        $this->success = $success;
        $this->message = $message;
        $this->data = $additionalData;
        $this->components = array();
    }

    public function add_response(PWP_I_Response_Component $response): void
    {
        $this->components[] = $response;
    }

    public function get_http_code(): int
    {
        return $this->httpCode;
    }

    public function get_data(): array
    {
        return $this->data;
    }

    public function to_array(): array
    {
        $response = array(
            'status' => $this->success ? 'success' : 'failure',
            'message' => $this->message,
            'data' => array(),
        );

        foreach ($this->data as $key => $data) {
            $response['data'][$key] = $data;
        }

        foreach ($this->components as $key => $component) {
            $response[$key] = $component->to_array();
        }

        return $response;
    }

    public static function success(string $message, array $additionalData = []): self
    {
        return new self($message, true, $additionalData);
    }

    public static function failure(string $message, array $additionalData = []): self
    {
        return new self($message, false, $additionalData);
    }
}
