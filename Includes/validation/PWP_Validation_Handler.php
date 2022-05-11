<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\notification\PWP_I_Notification;

class PWP_Validation_Handler extends PWP_Abstract_Term_Handler
{
    // public function handle(PWP_Term_SVC $service, PWP_Term_Data $request): PWP_I_Response
    // {
    //     return $this->handle_next($service, $request);
    // }

    public function handle(PWP_Term_Data $request, PWP_I_Notification $notification): bool
    {
        return $this->handle_next($request, $notification);
    }
}
