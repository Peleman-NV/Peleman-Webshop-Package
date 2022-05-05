<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\exceptions\PWP_API_Exception;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\wrappers\PWP_Term_Data;

class PWP_Validation_Handler extends PWP_Abstract_Term_Handler
{
    public function handle(PWP_Term_SVC $service, PWP_Term_Data $request): bool
    {
        try {
            return $this->handle_next($service, $request);
        } catch (PWP_API_Exception $exception) {
            throw $exception;
        }
    }
}
