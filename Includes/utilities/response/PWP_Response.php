<?php

declare(strict_types=1);

namespace PWP\includes\utilities\response;

class PWP_Response implements PWP_I_Response
{
    protected string $message;
    private array $data;
    /**
     * @var PWP_I_Response[]
     */
    private array $components;

    public function __construct(string $message, array $additionalData = [])
    {
        $this->message = $message;
        $this->data = $additionalData;
        $this->components = array();
    }

    public function add_response(PWP_I_Response $response): void
    {
        $this->components[] = $response;
    }

    public function get_data(): array
    {
        return $this->data;
    }

    public function to_array(): array
    {
        $response = array();
        $response['message'] = $this->message;

        foreach ($this->data as $key => $data) {
            $response['data'][$key] = $data;
        }

        foreach ($this->components as $key => $component) {
            $response[$key] = $component->to_array();
        }

        return $response;
    }
}
