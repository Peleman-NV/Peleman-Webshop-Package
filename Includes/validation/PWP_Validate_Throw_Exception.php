<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\exceptions\PWP_API_Exception;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\exceptions\PWP_Not_Found_Exception;
use PWP\includes\utilities\notification\PWP_I_Notification;

class PWP_Validate_Throw_Exception extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_Data $request, PWP_I_Notification $notification): bool
    {
        return PWP_Response::success("error handling successful.");
        return $this->handle_next($request, $notification);
    }
}
