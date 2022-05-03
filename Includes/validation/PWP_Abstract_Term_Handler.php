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
        echo ('<p>constructing...</p>');
        $this->service = $service;
        $this->next = null;
    }

    final public function set_next(PWP_Abstract_Term_Handler $next): PWP_Abstract_Term_Handler
    {
        $this->next = $next;
        return $this->next;
    }

    abstract public function handle(PWP_Term_Data $request): bool;

    final protected function handle_next(PWP_Term_Data $request): bool
    {
        return $this->next ? $this->next->handle($request) : true;
    }
}
