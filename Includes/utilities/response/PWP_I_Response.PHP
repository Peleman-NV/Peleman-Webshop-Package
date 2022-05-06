<?php

declare(strict_types=1);

namespace PWP\includes\utilities\response;

interface PWP_I_Response
{
    public function to_array(): array;
    public function is_success(): bool;
}
