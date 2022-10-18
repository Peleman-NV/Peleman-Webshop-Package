<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\utilities\notification\PWP_I_Notification;

class PWP_Validate_Throw_Exception extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_Data $request, PWP_I_Notification $notification): bool
    {
        return PWP_Response::success('success', __("Error handling successful.", PWP_TEXT_DOMAIN))->success;
        return $this->handle_next($request, $notification);
    }
}
