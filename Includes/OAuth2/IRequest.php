<?php

declare(strict_types=1);

namespace pwp\includes\OAuth2;

interface IRequest
{
    public function to_request_array(): array;
}
