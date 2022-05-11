<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\notification\PWP_I_Notification;
use PWP\includes\wrappers\PWP_Term_Data;

abstract class PWP_Abstract_Term_Handler
{
    private ?PWP_Abstract_Term_Handler $next;
    protected PWP_Term_SVC $service;

    public function __construct(PWP_Term_SVC $service)
    {
        $this->next = null;
        $this->service = $service;
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

    abstract public function handle(PWP_Term_Data $request, PWP_I_Notification $notification): bool;

    final protected function handle_next(PWP_Term_Data $request, PWP_I_Notification $notification): bool
    {
        return is_null($this->next)
            ? $notification->is_success()
            : $this->next->handle($request, $notification);
    }
}
