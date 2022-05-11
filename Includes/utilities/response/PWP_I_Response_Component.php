<?php

declare(strict_types=1);

namespace PWP\includes\utilities\response;

interface PWP_I_Response_Component
{
    public function to_array(): array;
}
