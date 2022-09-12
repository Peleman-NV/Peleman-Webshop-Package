<?php

declare(strict_types=1);

namespace PWP\includes\utilities\response;

interface PWP_I_Response
{
    public function add_response_component(PWP_I_Response $response): void;
    public function to_array(): array;
}
