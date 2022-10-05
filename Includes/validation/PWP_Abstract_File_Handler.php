<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\utilities\notification\PWP_I_Notification;
use PWP\includes\wrappers\PWP_File_Data;

abstract class PWP_Abstract_File_Handler
{
    private ?self $next = null;

    public function __construct()
    {
        $this->next = null;
    }

    final public function set_next(PWP_Abstract_File_Handler $next): PWP_Abstract_File_Handler
    {
        $this->next = $next;
        return $this->next;
    }

    abstract public function handle(PWP_File_Data $data, ?PWP_I_Notification $notification = null): bool;

    final protected function handle_next(PWP_File_Data $file, ?PWP_I_Notification $notification = null): bool
    {
        return is_null($this->next)
            ? ($notification->is_success() ?? true)
            : $this->next->handle($file, $notification);
    }
}
