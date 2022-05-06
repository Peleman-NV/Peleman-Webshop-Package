<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\wrappers\PWP_Term_Data;

abstract class PWP_Abstract_Term_Handler
{
    private ?PWP_Abstract_Term_Handler $next;

    public function __construct()
    {
        $this->next = null;
    }

    /**
     * set next link in chain of responsibility
     *
     * @param PWP_Abstract_Term_Handler $next
     * @return PWP_Abstract_Term_Handler
     */
    final public function set_next(PWP_Abstract_Term_Handler $next): PWP_Abstract_Term_Handler
    {
        $this->next = $next;
        return $this->next;
    }

    abstract public function handle(PWP_Term_SVC $service, PWP_Term_Data $request): PWP_I_Response;

    final protected function handle_next(PWP_Term_SVC $service, PWP_Term_Data $request): PWP_I_Response
    {
        return is_null($this->next)
            ? PWP_Response::success("validation chain complete")
            : $this->next->handle($service, $request);
    }
}
