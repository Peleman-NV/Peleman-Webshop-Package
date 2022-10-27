<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\Term_Data;
use PWP\includes\utilities\response\Response;
use PWP\includes\utilities\notification\I_Notification;

class Validate_Throw_Exception extends Abstract_Term_Handler
{
    public function handle(Term_Data $request, I_Notification $notification): bool
    {
        return Response::success('success', __("Error handling successful.", PWP_TEXT_DOMAIN))->success;
        return $this->handle_next($request, $notification);
    }
}
