<?php

declare(strict_types=1);

namespace PWP\includes\utilities\response;

class PWP_Response implements PWP_I_Response
{
    private bool $success;
    private int $httpCode;
    private array $data;
    protected string $message;
    /**
     * @var PWP_I_Response[]
     */
    private array $components;


    public function __construct(string $message, bool $success = true, int $httpCode = 200, array $additionalData = [])
    {
        $this->message = $message;
        $this->success = $success;
        $this->httpCode = $httpCode;
        $this->data = $additionalData;
        $this->components = array();
    }

    final public function add_response(PWP_I_Response $response): void
    {
        $this->components[] = $response;
    }

    final public function get_http_code(): int
    {
        return $this->httpCode;
    }

    final public function get_data(): array
    {
        return $this->data;
    }

    final public function get_components(): array
    {
        return $this->components;
    }

    public function to_array(): array
    {
        $response = array(
            'status' => $this->success ? 'success' : 'failure',
            'message' => $this->message,
        );

        if (!empty($this->data)) {
            $responseData = array();
            foreach ($this->data as $key => $data) {
                $responseData[$key] = $data;
            }
            $response['data'] = $responseData;
        }

        foreach ($this->components as $key => $component) {
            $response[] = $component->to_array();
        }

        return $response;
    }

    public static function success(string $message, int $httpCode = 200, array $additionalData = []): self
    {
        return new self($message, true, $httpCode, $additionalData);
    }

    public static function failure(string $message, int $httpCode = 400, array $additionalData = []): self
    {
        return new self($message, false, $httpCode, $additionalData);
    }
}
