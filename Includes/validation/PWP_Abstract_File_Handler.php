<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\utilities\notification\PWP_I_Notification;
use PWP\includes\wrappers\PWP_PDF_Upload;

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

    abstract public function handle(PWP_PDF_Upload $data, ?PWP_I_Notification $notification = null): bool;

    final protected function handle_next(PWP_PDF_Upload $file, ?PWP_I_Notification $notification = null): bool
    {
        return is_null($this->next)
            ? ($notification->is_success() ?? true)
            : $this->next->handle($file, $notification);
    }
}
