<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\utilities\notification\PWP_I_Notification;
use PWP\includes\wrappers\PWP_File_Component;

abstract class PWP_Abstract_File_Handler
{
    private ?self $next;

    public function __construct()
    {
        $this->next = null;
    }

    final public function set_next(self $next): self
    {
        $this->next = $next;
        return $this->next;
    }

    abstract public function handle(PWP_File_Component $file, PWP_I_Notification $notifictation): bool;

    final protected function handle_next(PWP_File_Component $file, PWP_I_Notification $notification): bool
    {
        return is_null($this->next)
            ? $notification->is_success()
            : $this->next->handle($file, $notification);
    }
}
