<?php

declare(strict_types=1);

namespace PWP\includes\utilities\response;

class PWP_Error_Response implements PWP_I_Response
{
    private int $code;
    private string $message;
    private array $data;

    public function __construct(string $message, int $code = 400, array $data = [])
    {
        $this->message = $message;
        $this->code = $code;
        $this->data = $data;
    }

    public function set_data(array $data): void
    {
        $this->data = $data;
    }

    public function get_data(): array
    {
        return $this->data;
    }

    public function add_data(string $key, $data): void
    {
        $this->data[$key] = $data;
    }

    public function to_array(): array
    {
        $array = array(
            'code' => $this->code,
            'message' => $this->message,
        );

        $array += $this->data;

        return $array;
    }

    public function get_code(): int
    {
        return $this->code;
    }

    public function add_response_component(PWP_I_Response $response): void
    {
    }
}
