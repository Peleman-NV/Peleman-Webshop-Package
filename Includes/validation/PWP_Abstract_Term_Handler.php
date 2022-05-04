<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\wrappers\PWP_Term_Data;

abstract class PWP_Abstract_Term_Handler
{
    private ?PWP_Abstract_Term_Handler $next;
    protected PWP_Term_SVC $service;

    public function __construct(PWP_Term_SVC $service)
    {
        $this->service = $service;
        $this->next = null;
    }

    final public function set_next(PWP_Abstract_Term_Handler $next): PWP_Abstract_Term_Handler
    {
        $this->next = $next;
        return $this->next;
    }

    /**
     * main validation handler function
     * 
     * will return `false` if validation is invalid. will automatically return `true` if validation is valid, or chain has reached its final link.
     *
     * @param PWP_Term_Data $request
     * @return boolean
     */
    abstract public function handle(PWP_Term_Data $request): bool;

    final protected function handle_next(PWP_Term_Data $request): bool
    {
        return $this->next ? $this->next->handle($request) : true;
    }
}
