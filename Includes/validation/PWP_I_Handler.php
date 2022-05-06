<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\wrappers\PWP_Component;
use PWP\includes\wrappers\PWP_I_Component;

interface PWP_I_Handler
{
    public function set_next(PWP_I_Handler $next): PWP_I_Handler;

    public function handle(PWP_Component $request): PWP_I_Response;
}
