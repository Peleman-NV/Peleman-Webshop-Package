<?php

declare(strict_types=1);

namespace PWP\includes\validation;

use PWP\includes\utilities\notification\I_Notification;
use PWP\includes\wrappers\Component;

interface I_Handler
{
    public function set_next(I_Handler $next): I_Handler;

    public function handle(Component $data, I_Notification $notification): bool;
    public function handle_next(Component $data, I_Notification $notification): bool;
}
