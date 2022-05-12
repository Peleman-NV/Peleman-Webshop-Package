<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\utilities\notification\PWP_I_Notification;

class PWP_Validation_Handler extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_Data $request, PWP_I_Notification $notification): bool
    {
        return $this->handle_next($request, $notification);
    }
}
